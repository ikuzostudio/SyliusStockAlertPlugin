<?php

namespace Ikuzo\SyliusStockAlertPlugin\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ikuzo\SyliusStockAlertPlugin\Form\StockAlertType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriptionController extends AbstractController
{
    public function __construct(
        private ChannelContextInterface $channelContext,
        private TranslatorInterface $translator,
        private ValidatorInterface $validator,
        private CustomerContextInterface $customerContext,
        private AvailabilityCheckerInterface $availabilityChecker,
        private ProductVariantRepositoryInterface $productVariantRepository,
        private SenderInterface $sender,
        private LocaleContextInterface $localeContext,
        private RepositoryInterface $stockAlertRepository,
        private FactoryInterface $stockAlertFactory,
    )
    {
    }

    public function addAction(Request $request): Response
    {
        $form = $this->createForm(StockAlertType::class);

        /** @var string|null $productVariantCode */
        $productVariantCode = $request->query->get('product_variant_code');
        if (is_string($productVariantCode)) {
            $form->setData(['product_variant_code' => $productVariantCode]);
        }

        $customer = $this->customerContext->getCustomer();
        if ($customer !== null && $customer->getEmail() !== null) {
            $form->remove('email');
        }

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if(!$form->isValid()) {
                $this->addFlash('error', $this->translator->trans('ikuzo_stock_alert.form.invalid_form'));
                return $this->redirect($request->headers->get('referer'));
            }
            $data = $form->all();
            /** @var StockAlertInterface $subscription */
            $stockAlert = $this->stockAlertFactory->createNew();
            if (!$this->channelContext->getChannel() instanceof ChannelInterface) {
                $this->addFlash('error', $this->translator->trans('ikuzo_stock_alert.form.invalid_channel'));
                return $this->redirect($request->headers->get('referer'));
            }
            $stockAlert->setChannel($this->channelContext->getChannel());
            if (array_key_exists('product_variant_code', $data)) {
                $productVariantCode = $data['product_variant_code']->getData();
                $productVariant = $this->productVariantRepository->findOneByCode($productVariantCode);
                if (!$productVariant instanceof ProductVariantInterface) {
                    $this->addFlash('error', $this->translator->trans('ikuzo_stock_alert.form.invalid_product_variant'));
                    return $this->redirect($request->headers->get('referer'));
                }
                $stockAlert->setProductVariant($productVariant);
            }
            if (array_key_exists('email', $data)) {
                $email = (string) $data['email']->getData();
                $errors = $this->validator->validate($email, [new Email(), new NotBlank()]);
                if (count($errors) > 0) {
                    $this->addFlash('error', $this->translator->trans('ikuzo_stock_alert.form.invalid_email'));
                    return $this->redirect($request->headers->get('referer'));
                }
                $stockAlert->setEmail($email);
            } elseif ($customer !== null) {
                $email = $customer->getEmail();
                if ($email !== null) {
                    $stockAlert->setCustomer($customer);
                    $stockAlert->setEmail($email);
                } else {
                    $this->addFlash('error', $this->translator->trans('ikuzo_stock_alert.form.invalid_form'));
                    return $this->redirect($request->headers->get('referer'));
                }
            } else {
                $this->addFlash('error', $this->translator->trans('ikuzo_stock_alert.form.invalid_form'));
                return $this->redirect($request->headers->get('referer'));
            }
            $this->stockAlertRepository->add($stockAlert);
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render(
            '@IkuzoSyliusStockAlertPlugin/stockAlertForm.html.twig',
            ['form' => $form->createView()],
        );
    }
}
