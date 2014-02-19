<?php

namespace Matthias\LeanpubApi;

use Matthias\LeanpubApi\Call\CreateCouponCallFactory;
use Matthias\LeanpubApi\Call\ListAllSalesCallFactory;
use Matthias\LeanpubApi\Client\ClientInterface;
use Matthias\LeanpubApi\Dto\CouponCollection;
use Matthias\LeanpubApi\Dto\CreateCoupon;
use Matthias\LeanpubApi\Dto\IndividualPurchases;
use Matthias\LeanpubApi\Dto\Purchase;
use Matthias\LeanpubApi\Call\ListCouponsCallFactory;

class LeanpubApi
{
    private $client;
    private $createCouponCallFactory;
    private $listAllSalesCallFactory;
    private $listCouponsCallFactory;

    public function __construct(
        ClientInterface $client,
        CreateCouponCallFactory $createCouponCallFactory,
        ListAllSalesCallFactory $listAllSalesCallFactory,
        ListCouponsCallFactory $listCouponsCallFactory
    ) {
        $this->client = $client;
        $this->createCouponCallFactory = $createCouponCallFactory;
        $this->listAllSalesCallFactory = $listAllSalesCallFactory;
        $this->listCouponsCallFactory = $listCouponsCallFactory;
    }

    /**
     * @return CouponCollection
     */
    public function listCoupons($bookSlug)
    {
        return $this->client->callApi($this->listCouponsCallFactory->create($bookSlug, 'json'));
    }

    /**
     * @return IndividualPurchases
     */
    public function listIndividualPurchases($bookSlug, $page = 1)
    {
        return $this->client->callApi($this->listAllSalesCallFactory->create($bookSlug, $page, 'json'));
    }

    /**
     * @return Purchase[]
     */
    public function getAllIndividualPurchases($bookSlug)
    {
        return new \RecursiveIteratorIterator(new IndividualPurchasesIterator($this->client, $this->listAllSalesCallFactory, $bookSlug));
    }

    /**
     * @return boolean
     */
    public function createCoupon($bookSlug, CreateCoupon $coupon)
    {
        return $this->client->callApi($this->createCouponCallFactory->create($bookSlug, $coupon));
    }
}
