<?php namespace App\MonsterId;

class MonsterGenerator
{
    private string $projectDir;

    public function __construct(
        string $projectDir
    )
    {
        $this->projectDir = $projectDir;
    }

    public function generate(string $seed, int $size): string
    {
        $parts = array(
            'legs' => rand(1, 5),
            'hair' => rand(1, 5),
            'arms' => rand(1, 5),
            'body' => rand(1, 15),
            'eyes' => rand(1, 15),
            'mouth' => rand(1, 10)
        );

        $monster = imagecreatetruecolor(120, 120);
        $white   = imagecolorallocate($monster, 40, 40, 40);
        imagefill($monster, 0, 0, $white);

        foreach ($parts as $part => $num) {
            $file = sprintf('%s/src/MonsterId/parts/%s_%s.png', $this->projectDir, $part, $num);
            $im = imagecreatefrompng($file);
            imageSaveAlpha($im, true);
            imagecopy($monster, $im, 0, 0, 0, 0, 120, 120);
            imagedestroy($im);
            if ($part == 'body') {
                $color = imagecolorallocate($monster, rand(20, 235), rand(20, 235), rand(20, 235));
                imagefill($monster, 60, 60, $color);
            }
        }
        srand();
        $out = imagecreatetruecolor($size, $size);
        imagecopyresampled($out, $monster, 0, 0, 0, 0, $size, $size, 120, 120);
        ob_start();
        imagepng($out);
        $image_data = ob_get_contents();

        ob_end_clean();
        $imageDataBase64 = base64_encode($image_data);
        imagedestroy($out);
        imagedestroy($monster);

        return $imageDataBase64;
    }
}
