<?php namespace App\Keep;

use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Utils;

class KeepBondingChecker
{
    const CONTRACT_METHOD_UNBONDED_VALUE = 'unbondedValue';

    private string $web3Provider;
    private string $bondingAddress;
    private string $projectDir;
    private string $keepBondingAbi;

    public function __construct(
        string $web3Provider,
        string $bondingAddress,
        string $projectDir
    )
    {
        $this->web3Provider = $web3Provider;
        $this->bondingAddress = $bondingAddress;
        $this->projectDir = $projectDir;
        $this->keepBondingAbi = file_get_contents(sprintf('%s/src/Keep/keepBonding.abi.json', $projectDir));
    }

    public function getUnbondedValue(string $address): string
    {
        $provider = new HttpProvider(new HttpRequestManager($this->web3Provider, 5));
        $contract = new Contract($provider, $this->keepBondingAbi);
        $unbondedValue = null;
        $contract
            ->at($this->bondingAddress)
            ->call(self::CONTRACT_METHOD_UNBONDED_VALUE, $address, function ($err, $data) use (&$unbondedValue) {
                if ($err !== null) {
                    throw new \RuntimeException("Error while balance checking: $err");
                }
                $unbondedWei = $data[0];
                $unbondedEth = Utils::fromWei($unbondedWei, 'ether');
                $unbondedValue = sprintf('%s.%s', $unbondedEth[0]->toString(), $unbondedEth[1]->toString());
            });

        return $unbondedValue;
    }
}
