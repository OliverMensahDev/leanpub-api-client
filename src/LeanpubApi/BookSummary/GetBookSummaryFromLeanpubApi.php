<?php
declare(strict_types=1);

namespace LeanpubApi\BookSummary;

use Assert\Assert;
use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use LeanpubApi\Common\ApiKey;
use LeanpubApi\Common\BaseUrl;
use LeanpubApi\Common\BookSlug;
use LeanpubApi\IndividualPurchases\CouldNotLoadIndividualPurchases;
use Safe\Exceptions\JsonException;
use function Safe\json_decode;

final class GetBookSummaryFromLeanpubApi implements GetBookSummary
{
    private ApiKey $apiKey;
    private BaseUrl $leanpubApiBaseUrl;

    public function __construct(ApiKey $apiKey, BaseUrl $leanpubApiBaseUrl)
    {
        $this->apiKey = $apiKey;
        $this->leanpubApiBaseUrl = $leanpubApiBaseUrl;
    }

    public function getBookSummary(BookSlug $bookSlug): BookSummary
    {
        $adapter = GuzzleAdapter::createWithConfig(
            [
                'timeout' => 10
            ]
        );

        $response = $adapter->sendRequest(
            new Request(
                'GET',
                sprintf(
                    '%s/%s.json?api_key=%s',
                    $this->leanpubApiBaseUrl->asString(),
                    $bookSlug->asString(),
                    $this->apiKey->asString()
                )
            )
        );

        $jsonData = $response->getBody()->getContents();

        try {
            $decodedData = json_decode($jsonData, true);
        } catch (JsonException $previous) {
            throw CouldNotLoadIndividualPurchases::becauseJsonDataIsInvalid($jsonData, $previous);
        }

        return BookSummary::fromJsonDecodedData($decodedData);
    }
}
