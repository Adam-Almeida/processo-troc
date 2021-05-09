<?php

namespace Source\Core;

/**
 * Class Upload
 * @package Source\Core
 * CLASSE DE UPLOAD :: UPLOAD DESENVOLVIDA POR ADAM ALMEIDA
 * PROCESSO TROC 2021
 */
class Upload
{

    /*** @var mixed*/
    protected $message;

    /*** @var string */
    protected string $newFileName;

    /*** @var string */
    private string $inputFile;

    /*** @var string */
    private string $fileName;

    /*** @var string */
    private string $dir;

    /**
     * @param string $inputFile
     * @param string $fileName
     * @param string $dir
     * @return $this|null
     */
    public function bootstrap(string $inputFile, string $fileName, string $dir): ?Upload
    {
        $this->inputFile = $inputFile;
        $this->fileName = $fileName;
        $this->dir = $dir;
        return $this;
    }

    /**
     * @return $this|null
     */
    public function file(): ?Upload
    {

        $getPost = filter_input(INPUT_GET, "post", FILTER_VALIDATE_BOOLEAN);

        if ($_FILES && !empty($_FILES["{$this->inputFile}"]["{$this->fileName}"])) {
            $fileUpload = $_FILES["{$this->inputFile}"];

            /*TIPOS PERMITIDOS*/
            $allowedTypes = [
                "image/jpg",
                "image/jpeg",
                "image/png"
            ];

            /*RENOMEANDO ARQUIVO*/

            $nameFile = time() . mb_strstr($fileUpload['name'], ".");
            $this->newFileName($nameFile);

            if (in_array($fileUpload['type'], $allowedTypes)) {
                if (move_uploaded_file($fileUpload['tmp_name'], __DIR__ . "/../../{$this->dir}/{$nameFile}")) {
                    return $this;
                } else {
                   return $this->message("error", "Erro Inesperado");
                }
            } else {
                $this->message("error", "Tipo de Arquivo não permitido");
            }

        } elseif ($getPost) {
                $this->message("error", "O arquivo é muito grande");
        } else {
            if ($_FILES) {
                $this->message("error", "Selecione um arquivo antes de enviar");
            }
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getMessage(): ?array
    {
        return $this->message;
    }


    /**
     * @param string $type
     * @param string $text
     * @return string[]|null
     */
    public function message(string $type, string $text): ?array
    {
        return $this->message = [
            "type" => $type,
            "text" => $text
        ];
    }

    /**
     * @return string
     */
    public function getNewFileName(): string
    {
        return $this->newFileName;
    }

    /**
     * @param string $name
     * @return string
     */
    public function newFileName(string $name): string
    {
        return $this->newFileName = $name;
    }

}