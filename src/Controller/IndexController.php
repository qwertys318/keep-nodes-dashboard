<?php namespace App\Controller;

use App\Entity\Node;
use App\Form\Exception\ValidationException;
use App\Form\FormErrorCompiler;
use App\Keep\KeepBondingChecker;
use App\MonsterId\MonsterGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    private string $siteName;
    private string $domain;
    private FormErrorCompiler $formErrorCompiler;
    private KeepBondingChecker $keepBondingChecker;
    private MonsterGenerator $monsterGenerator;
    private string $projectDir;

    public function __construct(
        string $siteName,
        string $domain,
        FormErrorCompiler $formErrorCompiler,
        KeepBondingChecker $keepBondingChecker,
        MonsterGenerator $monsterGenerator,
        string $projectDir
    )
    {
        $this->siteName = $siteName;
        $this->domain = $domain;
        $this->formErrorCompiler = $formErrorCompiler;
        $this->keepBondingChecker = $keepBondingChecker;
        $this->monsterGenerator = $monsterGenerator;
        $this->projectDir = $projectDir;
    }

    public function index()
    {
        $jsConfig = [
            'siteName' => $this->siteName,
            'domain' => $this->domain,
            'year' => date('Y'),
        ];
        $data = [
            'jsConfig' => addslashes(json_encode($jsConfig, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE)),
            'year' => date('Y'),
            'appName' => 'site',
            'pageName' => null,
            'frontDevPort' => '8090',
            'styles' => [
                'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900',
                'https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css',
            ],
            'scripts' => [],
        ];

        return $this->render('app.html.twig', $data);
    }

    public function addNode(Request $request)
    {
        try {
            /** @TODO CSRF protection */
            $formBuilder = $this->createFormBuilder(null, ['data_class' => Node::class, 'csrf_protection' => false]);
            $formBuilder->add('address', TextType::class);
            $form = $formBuilder->getForm();
            $form->submit($request->request->get('form', []));
            if (!$form->isValid()) {
                throw new ValidationException($form);
            }

            /** @var Node $node */
            $node = $form->getData();
            $unbondedValue = $this->keepBondingChecker->getUnbondedValue($node->getAddress());
            $node->setUnbondedValue($unbondedValue);
            $node->setImage($this->monsterGenerator->generate($node->getAddress(), 80));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($node);
            $entityManager->flush();

            return new JsonResponse([]);
        } catch (ValidationException $e) {
            $errors = $this->formErrorCompiler->compile($e->getForm());

            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function nodeList()
    {
        $list = [];
        $entityManager = $this->getDoctrine()->getManager();
        /** @var Node[] $nodes */
        $nodes = $entityManager->getRepository(Node::class)->findBy([], ['id' => 'DESC']);
        foreach ($nodes as $node) {
            $list[] = $node->compileListView($this);
        }

        return new JsonResponse(['items' => $list]);
    }
}
