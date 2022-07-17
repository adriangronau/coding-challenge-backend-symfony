<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Exception;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private OrderRepository $orderRepository;
    private OrderItemRepository $orderItemRepository;
    private ProductRepository $productRepository;

    public function __construct(
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->productRepository = $this->container->get(ProductRepository::class);
    }

    #[Route('/order', name: 'getOrders', methods: ['GET'])]
    public function getOrders(): JsonResponse
    {
        $orders = $this->orderRepository->findAllOrders();

        return $this->json($orders);
    }

    #[Route('/order/{orderType}/submit/{id}', name: 'createOrder', methods: ['POST'])]
    public function createOrder(Request $request, string $orderType, int $id): JsonResponse
    {
        $orderPayload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        try {
            $order = new Order();
            $order->setCustomerFirstname($orderPayload['customer']['firstName']);
            $order->setCustomerLastname($orderPayload['customer']['lastName']);
            $order->setCustomerEmail($orderPayload['customer']['email']);
            $order->setCustomerAddressStreet($orderPayload['customer']['address']['street']);
            $order->setCustomerAddressStreetNumber($orderPayload['customer']['address']['streetNumber']);
            $order->setCustomerAddressPostcode($orderPayload['customer']['address']['streetNumber']);
            $order->setCustomerAddressCity($orderPayload['customer']['address']['city']);

            if ($orderType === 'SYLIUS') {
                $order->setCustomerAddressCountry('Germany');
            } else if ($orderType === 'MAGENTO' || $orderType === 'SHOPWARE' || $orderType === 'SHOPIFY') {
                $order->setCustomerAddressCountry($orderPayload['customer']['address']['country']);
            } else {
                $emailSegments = explode(".", $orderPayload['customer']['email']);
                $order->setCustomerAddressCountry(end($emailSegments));
            }

            $this->orderRepository->persist($order);
            $this->orderRepository->flush();

            foreach ($orderPayload['items'] as $orderItemPayload) {
                $product = $this->productRepository->findProduct($orderItemPayload['productId']);
                $productVariant = $orderItemPayload['productVariant'];

                if ($product !== null) {
                    try {
                        $orderItem = new OrderItem();
                        $orderItem->setProduct($product);
                        $orderItem->setProductName($product->getName());
                        $orderItem->setAmount($orderItemPayload['amount']);
                        $orderItem->setBillableAmount($orderItemPayload['amount']);

                        if ($orderType === 'SYLIUS' && $productVariant === 'VOUCHER_CODE') {
                            $orderItem->setBillableAmount(0);
                            $orderItem->setForeignId(999_000_001);
                        }

                        $discounts = [];
                        if ($productVariant === 'DISCOUNT_CODE') {
                            $orderItem->setBillableAmount(0);
                            $orderItem->setForeignId(-1);

                            foreach ($orderPayload['discounts'] as $orderItemDiscountPayload) {
                                $subOrderItem = new OrderItem();
                                $subOrderItem->setProduct($product);
                                $subOrderItem->setForeignId(-1);

                                $this->orderItemRepository->persist($subOrderItem);
                                $this->orderItemRepository->flush();

                                $discounts[] = $subOrderItem;
                            }
                        }

                        if ($orderType === 'SHOPWARE' && count($discounts) >= 2) {
                            foreach ($discounts as $i => $discount) {
                                $discount->setForeignId(742_902_602);

                                if ($i >= 2) {
                                    $discount->setBillableAmount(0);
                                }

                                $this->orderItemRepository->persist($discount);
                                $this->orderItemRepository->flush();
                            }
                        }

                        $this->orderItemRepository->persist($orderItem);
                        $this->orderItemRepository->flush();
                    } catch (Exception $exception) {
                        continue;
                    }
                } else {
                    throw new Exception();
                }
            }
            $this->orderRepository->flush();
            $this->orderItemRepository->flush();

            return $this->json($order);
        } catch (JsonException $exception) {
            return $this->createDefaultError();
        }

        return $this->createDefaultError();
    }

    private function createDefaultError(?string $errorDescription = null): mixed
    {
        return $this->json([
            'status' => 500,
            'message' => $errorDescription ?? 'An unknown error occurred',
        ]);
    }
}
