<?php

namespace App\Tests\StateController;

use App\Entity\Transaction;
use DateTime;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function json_decode;


class StateController extends AbstractController
{
    protected EntityManager $em;

    /**
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @throws Exception
     */
    public function transactionServiceProviderState(Request $request): Response
    {
        $purger = new ORMPurger($this->em);
        $purger->setPurgeMode(2);
        $purger->purge();

        $content = json_decode($request->getContent(), true);

        $consumer = $content["consumer"];
        $state = $content["state"];

        if ($state == 'transactions exist in Transaction Service') {
            $this->setSuccessfulSale();
        }

        $response = new Response();
        $response->setContent("Successfully setup data for Transaction Service state");
        return $response;
    }

    /**
     * @throws Exception
     */
    public function setSuccessfulSale(): void
    {
        $this->em->getConnection()->executeStatement("ALTER SEQUENCE transactions.t_transaction_id_seq RESTART");
        $transactions = new Transaction();
        $transactions->setTrnKey("6262a4d086be2b8c35ff19d95896865e");
        $transactions->setMerchantIdent("8a6e52a9d2d84600a2cfa8cf0ef6282e");
        $transactions->setCurrency("BGN");
        $transactions->setCreateDate(DateTime::createFromFormat("Y-m-d H:i:s", "2022-04-14 16:22:30"));
        $transactions->setExecutionDate(DateTime::createFromFormat("Y-m-d H:i:s", "2022-04-14 16:22:30"));
        $transactions->setUserToken("1ee503de1908362fae12dfbf00a62d9b");
        $transactions->setChannel("mobile");
        $transactions->setPaymentMethod(1);
        $transactions->setAmount(1.1);
        $transactions->setTipAmount(0);
        $transactions->setTransactionType(12);
        $transactions->setStatus(1);
        $transactions->setTerminalId("16P19999");
        $transactions->setMcc("4");
        $transactions->setDeviceId("447c4e842bb80e68");
        $transactions->setCardToken("643417e5-8c0a-4ceb-8ee6-4f3e94089a75");
        $transactions->setCardPanObfuscated("999999XXXXXX9999"); //mandatory for receipt
        $transactions->setCardType("MASTERCARD");
        $transactions->setPosSystemTraceAuditNumber("333681C8CE971A37");
        $transactions->setPosApplicationId("A0000000041010");
        $transactions->setPosAcquiringInstitutionCode("1602160000");
        $transactions->setPosCardAcceptorIdentCode("1602071111");
        $transactions->setPosCardAcceptorName("Darth Vader");
        $transactions->setPosCardAcceptorCity("Sofia");
        $transactions->setPosCardAcceptorCountry("BG");
        $transactions->setPosLocalDateTime(DateTime::createFromFormat("Y-m-d H:i:s", "2022-04-14 16:22:30"));
        $transactions->setPosAuthCode("302105"); //mandatory for receipt
        $transactions->setIsDeleted(false);
        $transactions->setIsHidden(false);
        $transactions->setRefundableAmount(1.1);
        $transactions->setVoidable(true);
        $transactions->setTimezoneName("UTC");
        $transactions->setScaType(1);
        $transactions->setErrorCode("00"); //mandatory for receipt
        $this->em->persist($transactions);
        $this->em->flush();
    }
}
