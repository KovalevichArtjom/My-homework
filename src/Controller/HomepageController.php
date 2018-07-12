<?php
/**
 * Created by PhpStorm.
 * User: Professional
 * Date: 10.07.2018
 * Time: 1:28
 */

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Message;
use App\Entity\Product;
use Doctrine\ORM\Query\ResultSetMapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(){
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('Page/homepage.html.twig',[
            'db_category'   => $category
        ]);
    }

    /**
     * @param $category_slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\DBALException
     * @Route("/{category_slug}", name="show")
     */
    public function show($category_slug){

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findIdCategory($category_slug);

        $category_id=$category['0']['id'];

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findProducForCategory($category_id);

        $url = "/".$category_slug."";
        return $this->render('Page/category.html.twig',[
            'category_slug' => $category_slug,
            'category' => $category,
            'db_product' => $product,
            'url' => $url
        ]);
    }

    /**
     * @param $category_slug
     * @param $product_slug
     * @throws \Doctrine\DBAL\DBALException
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{category_slug}/{product_slug}", name="show_product")
     */
    public function product($category_slug,$product_slug){

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findProducText($product_slug);

        return $this->render('Page/product.html.twig',[
            'product_slug' => $product_slug,
            'category_slug' => $category_slug,
            "db_product" => $product
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/contacts/", name="app_contacts")
     */
    public function contacts(){
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

            return $this->render('Page/contacts.html.twig', [
                'db_category' => $category,
                'db_product' => $product
                ]);
    }

    /**
     * @Route("/contacts/add_product/", name="add_product")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function AddinDBproduct(Request $request)
    {
        $params = $request->request->all();


        $text = $params['text'];
        $name_product = $params['name_product'];
        $category_id = $params['category_id'];

        $title = "Продукт ".$name_product." был успешно добавлен!";

        $em = $this->getDoctrine()->getManager();

        $category=$em->getRepository("App:Category");
        $org = $category->find($category_id);


        $product = new Product();

        $product ->setNameProduct($name_product);
        $product ->setText($text);
        $product->setCategory($org);

        $em  ->persist($product);
        $em  ->persist($org);
        $em  ->flush();

        $products_all = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('Page/addindb.html.twig',[
            'db_products_all'   => $products_all,
            'title' => $title
        ]);
    }

    /**
     * @Route("/contacts/del_product/", name="del_product")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function DelinDBproduct(Request $request)
    {
        $params = $request->request->all();

        $product_id = $params['product_id'];

        $title = "Продукт был успешно удалён!";

        $em = $this->getDoctrine()->getManager();

        $product = $em ->getRepository(Product::class)->find($product_id);

        $em  ->remove($product);
        $em  ->flush();

        $products_all = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('Page/addindb.html.twig',[
            'db_products_all'   => $products_all,
            'title' => $title
        ]);
    }

    /**
     * @Route("/contacts/message/", name="add_feedback")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function addFeedback(Request $request)
    {
        $params = $request->request->all();

        $surname = $params['Surname'];
        $name = $params['Firsname'];
        $email = $params['email'];
        $text = $params['question'];

        $title = "Ваше письмо успешно отправлено!";

        $em = $this->getDoctrine()->getManager();
        $message = new Message();
        $message ->setSurname($surname);
        $message ->setName($name);
        $message ->setEmail($email);
        $message ->setText($text);

        $em  ->persist($message);
        $em ->flush();

        return $this->render('Page/message.html.twig', [
            'title' => $title
        ]);

    }
}