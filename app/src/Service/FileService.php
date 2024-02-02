<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileService extends EncryptionService
{

    const SPLITTER = '$';

    public function __construct(string $key, string $cipher, string $algorithm)
    {
        parent::__construct( $key,  $cipher,  $algorithm);
    }

    /**
     * @param string $target
     * @return string
     */
    public function link($target = 'presentation.pdf'): string
    {
        return $this->encrypt($target);
    }

    /**
     * @param $string
     * @return array
     */
    public function analysis($string): array
    {
        $decrypted = explode(self::SPLITTER, $this->decrypt($string));

        if (count($decrypted) !== 2) {
            throw new \ValueError('Wrong exploded string');
        }

        return [intval($decrypted[0]), $decrypted[1]];
    }

    /**
     * @param $string
     * @return Response
     */
    public function download($string): Response
    {

        try {
            [$time, $filename] = $this->analysis($string);
        } catch (\ValueError $e) {
            throw new NotFoundHttpException('File not found');
        }

        if ($time < time()) {
            throw new NotFoundHttpException('File not found');
        }

        try {
            $response = new Response();
            $response->headers->set('Cache-Control', 'private');
            $response->headers->set('Content-type', 'text/plain');
            $response->headers->set('Content-Disposition', sprintf('attachment;filename=%s', $filename));
            $response->setStatusCode(Response::HTTP_OK);
            $response->sendHeaders();
            $response->setContent(bin2hex(random_bytes(10)));
            return $response;
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

    }

}