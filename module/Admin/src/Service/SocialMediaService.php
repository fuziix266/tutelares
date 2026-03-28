<?php
namespace Admin\Service;

class SocialMediaService
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Publica una noticia en una página de Facebook.
     */
    public function postToFacebook(string $title, string $message, ?string $imageUrl = null): array
    {
        $accessToken = $this->config['facebook']['access_token'] ?? '';
        $pageId      = $this->config['facebook']['page_id'] ?? '';

        if (empty($accessToken) || empty($pageId)) {
            return ['success' => false, 'error' => 'Configuración de Facebook incompleta.'];
        }

        $url = "https://graph.facebook.com/v19.0/{$pageId}/";
        $params = [
            'access_token' => $accessToken,
            'message'      => $title . "\n\n" . $message,
        ];

        if ($imageUrl) {
            $url .= "photos";
            $params['url'] = $imageUrl;
        } else {
            $url .= "feed";
        }

        return $this->makeRequest($url, $params);
    }

    /**
     * Publica una noticia en Instagram (mediante la API de Graph).
     * Requiere que la cuenta de Instagram esté vinculada a una página de FB.
     */
    public function postToInstagram(string $title, string $message, string $imageUrl): array
    {
        $accessToken = $this->config['instagram']['access_token'] ?? '';
        $igUserId    = $this->config['instagram']['user_id'] ?? '';

        if (empty($accessToken) || empty($igUserId)) {
            return ['success' => false, 'error' => 'Configuración de Instagram incompleta.'];
        }

        if (empty($imageUrl)) {
            return ['success' => false, 'error' => 'Instagram requiere una imagen para publicar.'];
        }

        // 1. Crear el contenedor de medios
        $containerUrl = "https://graph.facebook.com/v19.0/{$igUserId}/media";
        $containerParams = [
            'access_token' => $accessToken,
            'image_url'    => $imageUrl,
            'caption'      => $title . "\n\n" . $message,
        ];

        $containerRes = $this->makeRequest($containerUrl, $containerParams);

        if (!$containerRes['success']) return $containerRes;

        $creationId = $containerRes['data']['id'] ?? null;

        if (!$creationId) {
            return ['success' => false, 'error' => 'No se pudo obtener el ID del contenedor de Instagram.'];
        }

        // 2. Publicar el contenedor
        $publishUrl = "https://graph.facebook.com/v19.0/{$igUserId}/media_publish";
        $publishParams = [
            'access_token'      => $accessToken,
            'creation_id'       => $creationId,
        ];

        return $this->makeRequest($publishUrl, $publishParams);
    }

    /**
     * Realiza un request POST usando cURL.
     */
    private function makeRequest(string $url, array $params): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Para evitar problemas locales de SSL con cacert.pem si no está configurado

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            return ['success' => false, 'error' => "cURL Error: " . $error];
        }

        $data = json_decode($response, true);

        if ($httpCode >= 400) {
            $msg = $data['error']['message'] ?? 'Error desconocido de la API.';
            return ['success' => false, 'error' => "API Error ({$httpCode}): " . $msg, 'data' => $data];
        }

        return ['success' => true, 'data' => $data];
    }
}
