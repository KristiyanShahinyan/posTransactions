<?php

namespace App\Controller;

use App\Dto\Request\AnalyticsBalanceFilterDto;
use App\Dto\Request\AnalyticsDto;
use App\Service\AnalyticsServiceInterface;
use Phos\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use DateTimeZone;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

/**
 * Class AnalyticsController
 * @package App\Controller
 */
class AnalyticsController extends AbstractApiController
{
    protected const STATUS_APPROVED = 1;
    protected const STATUS_DECLINED = -1;
    
    /**
     * @param string $userToken
     * @param Request $request
     * @param AnalyticsServiceInterface $analyticsService
     * @return JsonResponse
     * @throws \Exception
     */
    public function statsByPeriod(string $userToken, Request $request, AnalyticsServiceInterface $analyticsService)
    {
        /** @var AnalyticsDto $dto */
        $dto = $this->deserialize($request->getContent(), AnalyticsDto::class, [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]);

        if ($dto->getTimeZone() === 'GMT') {
            $dto->setTimeZone('UTC');
        }

        $this->validate($dto);

        $pivotPoint = new DateTime($dto->getDate()->format('Y-m-d'));

        return $this->success($analyticsService->getAnalyticsData($userToken, $pivotPoint, $dto->getType()));
    }

    public function statsByPeriodCounts(Request $request, AnalyticsServiceInterface $analyticsService)
    {
        /** @var AnalyticsBalanceFilterDto $dto */
        $dto = $this->deserialize($request->getContent(), AnalyticsBalanceFilterDto::class, [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]);
        
        $timezone = $dto->getTimeZone();
        if (!$timezone || $timezone === 'GMT') {
            $dto->setTimeZone('UTC');
        }
        
        $this->validate($dto);
        
        $approvedStats = $analyticsService->getAnalyticsStats($dto, self::STATUS_APPROVED);
        $declinedStats = $analyticsService->getAnalyticsStats($dto, self::STATUS_DECLINED);
        
        return $this->success([
            'startDate' => $approvedStats['startDate'],
            'endDate' => $approvedStats['endDate'],
            'sales' => $approvedStats['sales'],
            'approvedCounts' => $approvedStats['counts'],
            'declinedCounts' => $declinedStats['counts'],
        ]);
    }
}
