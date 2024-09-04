<?php 

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;
use App\Models\ApiKeyModel;

class ApiKeyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Handle CORS preflight requests (OPTIONS method)
        if ($request->getMethod() === 'OPTIONS') {
            return Services::response()
                ->setStatusCode(ResponseInterface::HTTP_OK)
                ->setHeader('Access-Control-Allow-Origin', '*') // or the allowed origin
                ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        }

        // Get the Authorization header
        $authHeader = $request->getHeaderLine('Authorization');

        // Check if the Authorization header is present and starts with 'Bearer'
        if ($authHeader && strpos($authHeader, 'Bearer ') === 0) {
            // Extract the API key from the Bearer token
            $apiKey = substr($authHeader, 7);

            $apiKeyModel = new ApiKeyModel();
            $apiKeyRecord = $apiKeyModel->where('api_key', $apiKey)->first();

            // Validate the API key
            if (!$apiKeyRecord) {
                return Services::response()
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                    ->setHeader('Access-Control-Allow-Origin', '*') // or the allowed origin
                    ->setJSON(['error' => 'Invalid API key']);
            }
        } else {
            // Return an error if the Authorization header is missing or does not contain a Bearer token
            return Services::response()
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setHeader('Access-Control-Allow-Origin', '*') // or the allowed origin
                ->setJSON(['error' => 'Authorization header missing or incorrect']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}