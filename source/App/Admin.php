<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Core\Upload;
use Source\Models\Auth;
use Source\Models\Product;
use Source\Models\Ticket;


/**
 * Class Admin
 * @package Source\App
 */
class Admin
{
    /**
     * @var Engine
     */
    protected $view;

    /**
     * Admin constructor.
     */
    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../theme/", "php");

        if (!Auth::user()){
            redirect("/login");
        }


    }


    /**
     *
     */
    public function adminArea(): void
    {

        $tickets = (new Ticket())->find()->fetch(true);

        echo $this->view->render("dash", [
            "title" => "CUPONS | PROCESSO TROC",
            "tickets" => $tickets,
            "user" => Auth::user()->first_name ." ". Auth::user()->last_name
        ]);

    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function ticket(array $data): void
    {
        $ticketPost = (object)filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($ticketPost->due_date) || empty($ticketPost->value)) {
            echo $this->adminArea();
            die();
        }

        $dueDate = dueDate($ticketPost->due_date, "+");
        $ticketCode = strtoupper(substr(bin2hex(random_bytes(7)), 1));

        $ticket = (new Ticket())->bootstrap(
            $ticketCode,
            $dueDate,
            $ticketPost->value,
            $ticketPost->ticket_type
        );

        $ticket->save();
        echo $this->adminArea();

    }

    /**
     * @param array $data
     */
    public function ticketUpdate(array $data): void
    {
        $ticketPost = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        //** VALIDAÇÃO DE ID */

        $idTicket = filter_var($data['id'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$idTicket || $idTicket == '' || !is_numeric($idTicket)) {
            redirect("/admin/dash");
            die();
        }

        $update = (new Ticket())->ticketUpdate($ticketPost, $idTicket);
        if ($update) {
            redirect("/admin/dash");
        } else {
            redirect("/admin/dash");
        }
    }

    /**
     * @param array $data
     */
    public function ticketEdit(array $data): void
    {
        //** VALIDAÇÃO DE ID */

        $idTicket = filter_var($data['id'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$idTicket || $idTicket == '' || !is_numeric($idTicket)) {
            redirect("/admin/dash");
        }

        $edit = (new Ticket())->findById($idTicket);
        $tickets = (new Ticket())->find()->fetch(true);

        /* REFATORAR :: IMPLEMENTAR A CLASSE ->  MESSAGE */

        echo $this->view->render("dash", [
            "title" => "TICKET | PROCESSO TROC",
            "edit" => $edit,
            "tickets" => $tickets,
            "user" => Auth::user()->first_name ." ". Auth::user()->last_name
        ]);

    }

    /**
     * @param array $data
     */
    public function ticketDelete(array $data): void
    {
        //** VALIDAÇÃO DE ID */

        $idTicket = filter_var($data['id'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$idTicket || $idTicket == '' || !is_numeric($idTicket)) {
            redirect("/admin/dash");
            return;
        }

        /* REFATORAR :: IMPLEMENTAR A CLASSE ->  MESSAGE */
        $delete = (new Ticket())->ticketDestroy($idTicket);

        if ($delete) {
            redirect("/admin/dash");
        } else {
            redirect("/admin/dash");
        }
    }

    /**
     *
     */
    public function adminProduct(): void
    {
        //** DEPENDENCIA DA CLASSE UPLOAD*/
        $getPost = filter_input(INPUT_GET, "post", FILTER_VALIDATE_BOOLEAN);

        $formPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($formPost) {

            $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (in_array("", $post)) {
                $erro = "Preencha todos os campos";

                echo $this->view->render("admin-product", [
                    "title" => "PRODUTOS | PROCESSO TROC",
                    "products" => (new Product())->find()->fetch(true),
                    "message" => [
                        "text" => $erro,
                        "type" => "error"
                    ],
                    "user" => Auth::user()->first_name ." ". Auth::user()->last_name
                ]);

            } else {

                /*COVERTENDO OS DADOS PARA UM OBJETO*/
                $dataProduct = (object)$post;

                $formCover = (new Upload())->bootstrap("file", "name", "storage"
                )->file();

                if (!empty($formCover->getMessage())) {

                    $messageFile = $formCover->getMessage();

                    echo $this->view->render("admin-product", [
                        "title" => "PRODUTOS | PROCESSO TROC",
                        "products" => (new Product())->find()->fetch(true),
                        "message" => [
                            "text" => $messageFile['text'],
                            "type" => $messageFile['type']
                        ],
                        "user" => Auth::user()->first_name ." ". Auth::user()->last_name
                    ]);
                } else {

                    $cover = $formCover->getNewFileName();

                    $product = (new Product())->bootstrap(
                        $dataProduct->name,
                        $cover,
                        $dataProduct->value,
                        $dataProduct->category,
                        $dataProduct->description
                    );

                    /* REFATORAR :: EVNIAR PARA O MODELO DE PRODUTO / CLASSE MESSAGE */
                    $product->save();

                    echo $this->view->render("admin-product", [
                        "title" => "PRODUTOS | PROCESSO TROC",
                        "products" => (new Product())->find()->fetch(true),
                        "message" => [
                            "text" => "Cadastro realizado com Sucesso",
                            "type" => "success",
                        ],
                        "user" => Auth::user()->first_name ." ". Auth::user()->last_name
                    ]);
                }
            }
        } else {

            $products = (new Product())->find()->fetch(true);
            echo $this->view->render("admin-product", [
                "title" => "PRODUTOS | PROCESSO TROC",
                "products" => $products,
                "user" => Auth::user()->first_name ." ". Auth::user()->last_name
            ]);
        }
    }

    /**
     * @param array $data
     */
    public function productEdit(array $data): void
    {
        //** VALIDAÇÃO DE ID */

        $idProduct = filter_var($data['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        $idProduct = filter_var($idProduct, FILTER_VALIDATE_INT);

        if ($idProduct) {

            $product = (new Product())->findById($idProduct);

            echo $this->view->render("admin-product", [
                "title" => "EDITAR PRODUTOS | PROCESSO TROC",
                "products" => (new Product())->find()->fetch(true),
                "edit" => $product,
                "user" => Auth::user()->first_name ." ". Auth::user()->last_name
            ]);
        } else {

            $text = ("Erro ao buscar o produto" ?? null);
            $type = ("warning" ?? null);

            echo $this->view->render("admin-product", [
                "title" => "EDITAR PRODUTOS | PROCESSO TROC",
                "products" => (new Product())->find()->fetch(true),
                "message" => [
                    "text" => $text,
                    "type" => $type
                ],
                "user" => Auth::user()->first_name ." ". Auth::user()->last_name
            ]);
        }
    }

    /**
     * @param array $data
     */
    public function productUpdate(array $data): void
    {

        //** VALIDAÇÃO DE ID */

        $idProduct = filter_var($data['id'], FILTER_SANITIZE_SPECIAL_CHARS);
        $idProduct = filter_var($idProduct, FILTER_VALIDATE_INT);

        //** DEPENDENCIA DA CLASSE UPLOAD */
        $getPost = filter_input(INPUT_GET, "post", FILTER_VALIDATE_BOOLEAN);

        $formPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($idProduct && $formPost) {

            $dataUpdate = filter_var_array($formPost, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($_FILES['file']['name']) {

                $formCover = (new Upload())->bootstrap("file", "name", "storage"
                )->file();

                if (!empty($formCover->getMessage())) {

                    $messageFile = $formCover->getMessage();

                    echo $this->view->render("admin-product", [
                        "title" => "PRODUTOS | PROCESSO TROC",
                        "products" => (new Product())->find()->fetch(true),
                        "message" => [
                            "text" => $messageFile['text'],
                            "type" => $messageFile['type']
                        ],
                        "user" => Auth::user()->first_name ." ". Auth::user()->last_name
                    ]);
                }

                $dataUpdate['cover'] = $formCover->getNewFileName();
            }

            $update = (new Product());
            $update->productUpdate($dataUpdate, $idProduct);

            $text = ($update->getMessage()->text ?? null);
            $type = ($update->getMessage()->type ?? null);

            echo $this->view->render("admin-product", [
                "title" => "PRODUTOS | PROCESSO TROC",
                "products" => (new Product())->find()->fetch(true),
                "message" => [
                    "text" => $text,
                    "type" => $type
                ],
                "user" => Auth::user()->first_name ." ". Auth::user()->last_name
            ]);

        }
    }


    /**
     * @param array $data
     * CONTROLADOR PARA ESCLUSÃO DE PRODUTOS
     */
    public function productDelete(array $data): void
    {
        //** VALIDAÇÃO DE ID */
        $idProduct = filter_var($data['id'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$idProduct || $idProduct == '' || !is_numeric($idProduct)) {
            redirect("/admin/dash");
            die();
        }

        $delete = (new Product());

        $delete->productDestroy($idProduct);

        $text = ($delete->getMessage()->text ?? null);
        $type = ($delete->getMessage()->type ?? null);

        echo $this->view->render("admin-product", [
            "title" => "PRODUTOS | PROCESSO TROC",
            "products" => (new Product())->find()->fetch(true),
            "message" => [
                "text" => $text,
                "type" => $type
            ],
            "user" => Auth::user()->first_name ." ". Auth::user()->last_name
        ]);
    }

    /**
     *
     */
    public function exit(): void
    {
        Auth::logout();
        redirect("/login");
    }

}
