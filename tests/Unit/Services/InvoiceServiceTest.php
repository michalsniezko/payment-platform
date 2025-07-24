<?php
declare(strict_types=1);

namespace Services;

use App\Services\EmailService;
use App\Services\InvoiceService;
use App\Services\PaymentGatewayService;
use App\Services\SalesTaxService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class InvoiceServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testItProcessesInvoice(): void
    {
        $salesTaxServiceMock = $this->createMock(SalesTaxService::class);
        $gatewayServiceMock = $this->createMock(PaymentGatewayService::class);
        $emailServiceMock = $this->createMock(EmailService::class);

        $gatewayServiceMock->method('charge')->willReturn(true);

        $invoiceService = new InvoiceService(
            $salesTaxServiceMock,
            $gatewayServiceMock,
            $emailServiceMock
        );

        $customer = ['name' => 'Mike'];
        $amount = 150;

        $result = $invoiceService->process($customer, $amount);

        $this->assertTrue($result);
    }

    /**
     * @throws Exception
     */
    public function testItSendsReceiptEmailWhenInvoiceIsProcessed(): void
    {
        $salesTaxServiceMock = $this->createMock(SalesTaxService::class);
        $gatewayServiceMock = $this->createMock(PaymentGatewayService::class);
        $emailServiceMock = $this->createMock(EmailService::class);

        $gatewayServiceMock->method('charge')->willReturn(true);
        $emailServiceMock
            ->expects($this->once())
            ->method('send')
            ->with(['name' => 'Mike'], 'receipt');

        $invoiceService = new InvoiceService(
            $salesTaxServiceMock,
            $gatewayServiceMock,
            $emailServiceMock
        );

        $customer = ['name' => 'Mike'];
        $amount = 150;

        $result = $invoiceService->process($customer, $amount);

        $this->assertTrue($result);
    }
}
