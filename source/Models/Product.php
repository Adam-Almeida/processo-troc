<?php

namespace Source\Models;

use PDOException;
use Source\Core\Model;

/**
 * Class Product
 * @package Source\Models
 * MODELO DE PRODUTO - DESENVOLVIDO POR ADAM ALMEIDA
 * PROCESSO TROC 2021
 */
class Product extends Model
{
    /** @var */
    protected $message;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        parent::__construct("products", ['id'], ['name', 'cover', 'value', 'category', 'description']);
    }

    /**
     * MÉTODO DE INCIALIZAÇÃO/ALIMENTAÇÃO DO OBJETO
     * @param string $name
     * @param string $cover
     * @param string $value
     * @param string $category
     * @param string $description
     * @return $this|null
     */
    public function bootstrap(string $name, string $cover, string $value, string $category, string $description): ?Product
    {
        $this->name = $name;
        $this->cover = $cover;
        $this->value = $value;
        $this->category = $category;
        $this->description = $description;
        return $this;
    }

    /**
     * MÉTODO DE ATUALIZAÇÃO DO PRODUTO
     * @param array $data
     * @param int $idProduto
     * @return bool
     */
    public function productUpdate(array $data, int $idProduto): bool
    {
        try {

            if (!$this->findById($idProduto)) {
                return false;
            }

            $update = $this->findById($idProduto);

            if (empty($data['cover'])) {
                $update->update($data, "id = :id", "id={$idProduto}");
                $this->message("success", "Produto atualizado com sucesso");
                return true;
            } else {
                $cover = __DIR__ . "/../../storage/{$update->cover}";

                if (file_exists($cover) && is_file($cover)) {
                    unlink($cover);
                } else {
                    $this->message("error", "Erro ao excluir imagem");
                    return false;
                }
            }

            $update->update($data, "id = :id", "id={$idProduto}");
            $this->save();

            $this->message("success", "Produto atualizado com sucesso");
            return true;

        } catch (PDOException $exception) {
            $this->message("warning", $exception);
            return false;
        }
    }

    /**
     * MÉTODO DE EXCLUSÃO DE PRODUTO
     * @param int $id
     * @return bool
     */
    public function productDestroy(int $id): bool
    {
        try {

            $product = $this->findById($id);

            if (!$product) {
                return false;
            }

            $cover = __DIR__ . "/../../storage/{$product->cover}";

            if (file_exists($cover) && is_file($cover)) {
                unlink($cover);
            } else {
                $this->message("error", "Erro ao excluir imagem");
                return false;
            }

            $this->delete("id = :id", "id={$id}");
            $this->message("success", "Produto excluido com sucesso");
            return true;

        } catch (PDOException $exception) {
            $this->message("warning", $exception);
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $type
     * @param string $text
     * @return object
     */
    public function message(string $type, string $text): object
    {
        return $this->message = (object)[
            "type" => $type,
            "text" => $text
        ];
    }

}
