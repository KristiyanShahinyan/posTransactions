<?php

namespace App\Controller;

use App\Builder\TransactionBuilder;
use App\Dto\Request\TransactionFilterDto;
use App\Dto\Request\TransactionUpdateRefundDto;
use App\Entity\Transaction;
use App\Manager\TransactionManager;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\TransactionRequiredException;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Phos\Controller\AbstractApiController;
use Phos\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

/**
 * Class TransactionController.
 */
class TransactionController extends AbstractApiController
{
    /**
     * @var TransactionManager
     */
    protected $manager;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * TransactionController constructor.
     * @param TransactionManager $manager
     * @param PaginatorInterface $paginator
     */
    public function __construct(TransactionManager $manager, PaginatorInterface $paginator)
    {
        $this->manager = $manager;
        $this->paginator = $paginator;
    }

    /**
     * @param Request $request
     * @param int $page
     * @param int $limit
     *
     * @return JsonResponse
     * @throws ApiException
     */
    public function index(Request $request, int $page = 1, int $limit = 20): JsonResponse
    {
        /**
         * @var TransactionFilterDto $filters
         */
        $filters = $this->deserialize($request->getContent(), TransactionFilterDto::class, [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]);

        $this->validate($filters);

        $transactionBuilder = new TransactionBuilder();
        $transactionsQuery = $this->manager->loadTransactions($filters);

        /** Get result and pages count */
        $transactionsFinder = $this->paginator->paginate($transactionsQuery, $filters ? $filters->getPage() ?? $page : $page, $filters ? $filters->getLimit() ?? $limit : $limit);

        $transactions = $transactionBuilder->buildTransactionList($transactionsFinder);

        $transactions->setTotalItems($transactionsFinder->getTotalItemCount());
        $transactions->setTotalPages(ceil($transactionsFinder->getTotalItemCount() / $limit));

        return $this->success($transactions, ['groups' => ['index', 'list']]);
    }

    /**
     * @param $trnKey
     *
     * @return JsonResponse
     * @throws ApiException
     */
    public function show(string $trnKey): JsonResponse
    {
        $transaction = $this->manager->repository->findOneBy(compact('trnKey'));

        return $this->success($transaction, ['groups' => ['index']]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function create(Request $request): JsonResponse
    {
        /**
         * @var $transaction Transaction
         */
        $transaction = $this->manager->create($request->getContent());

        return $this->success($transaction, ['groups' => 'index']);
    }

    /**
     * @param Request $request
     * @param $trnKey
     *
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ApiException
     */
    public function update(Request $request, $trnKey): JsonResponse
    {
        /**
         * @var Transaction $transaction
         */
        $transaction = $this->manager->repository->findOneBy(compact('trnKey'));

        $this->manager->update($request->getContent(), $transaction, true);

        return $this->success($transaction, ['groups' => 'index']);
    }

    /**
     * @param Request $request
     * @param $trnKey
     *
     * @return JsonResponse
     *
     * @throws ApiException
     * @throws ConnectionException
     * @throws NonUniqueResultException
     * @throws TransactionRequiredException
     */
    public function refund(Request $request, $trnKey): JsonResponse
    {
        /** @var TransactionUpdateRefundDto $transactionUpdateRefund */
        $transactionUpdateRefund = $this->deserialize($request->getContent(), TransactionUpdateRefundDto::class, [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]);

        $transaction = $this->manager->secureUpdateRefundable(-$transactionUpdateRefund->getRefundableAmount(), $trnKey);

        return $this->success($transaction, ['groups' => 'index']);
    }
}
