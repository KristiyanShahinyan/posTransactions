<?php

namespace App\Tests\Provider;

use GuzzleHttp\Psr7\Uri;
use LogicException;
use PhpPact\Standalone\Installer\Exception\FileDownloadFailureException;
use PhpPact\Standalone\Installer\Exception\NoDownloaderFoundException;
use PhpPact\Standalone\ProviderVerifier\Model\VerifierConfig;
use PhpPact\Standalone\ProviderVerifier\Verifier;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Process\Process;

class TransactionServiceProviderTests extends KernelTestCase
{
    protected String $symfony_home;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test', 'debug' => false]);

        if ('test' !== self::$kernel->getEnvironment()) {
            throw new LogicException('Execute only in Test environment!');
        }
        $os_name = php_uname('s');

        $this->symfony_home = ($os_name === 'Linux' || $os_name === 'Darwin') ?
            shell_exec('which symfony') : shell_exec('where symfony');

        $this->symfony_home = str_replace("\n", "", $this->symfony_home);
        $command = [$this->symfony_home, 'server:start', '-d', '--port=7203'];

        $process = new Process($command, null);
        $process->mustRun();
    }

    /**
     * @throws FileDownloadFailureException
     * @throws NoDownloaderFoundException
     */
    public function testApiServiceConsumer()
    {

        $config = new VerifierConfig();
        $config
            ->setProviderName('Transaction Service')
            ->setProviderVersion(exec('git rev-parse HEAD')) // replace with env var, overwrite in CI
            ->setBrokerUri(new Uri(getenv("PACT_BROKER_URI")))
            ->setProviderBaseUrl(new Uri('http://localhost:7203'))
            ->setProviderStatesSetupUrl(new Uri('http://localhost:7203/transaction_provider_states'))
            ->setPublishResults(true);

        $verifier = new Verifier($config);
        $verifier->verify("API Service");

        $this->assertTrue(true, 'Pact Verification has failed.');
    }

    protected function tearDown(): void
    {
        $p = new Process([$this->symfony_home, 'server:stop'], null);
        $p->mustRun();
        $p->stop();
        parent::tearDown();
    }
}
