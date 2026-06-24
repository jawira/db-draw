<?php

namespace Jawira\DbDrawTests;

use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\View;
use Jawira\DbDraw\Service\ElementFilter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(\Jawira\DbDraw\Service\ElementFilter::class)]
class ElementFilterTest extends TestCase
{
  /**
   * @var \Jawira\DbDraw\Service\ElementFilter
   */
  private ElementFilter $elementFilter;

  private static array $tableSet1 = [
    'analytics.q1_sales_2025',
    'archive.orders_2023',
    'compliance.gdpr_requests',
    'content.page_revisions',
    'finance.accounts_receivable',
    'integration.oauth_tokens',
    'marketing.ab_test_results',
    'media.image_metadata',
    'monitoring.service_uptime',
    'public.users',
    'reporting.customer_summary_v2',
    'security.user_sessions',
    'staging.temp_import_001',
    'support.kb_articles',
    'workflow.approval_chains',
  ];

  private static array $tableSet2 = [
    'billing.subscription_plans',
    'billing.subscription_usage',
    'data_lake.processed_events',
    'data_lake.raw_events',
    'etl.job_run_errors',
    'etl.job_runs',
    'geo.country_codes',
    'geo.postal_codes',
    'identity.api_keys',
    'identity.service_accounts',
    'iot.device_registry',
    'iot.device_telemetry',
    'ml.feature_store',
    'ml.model_versions',
    'platform.feature_flags',
  ];


  public function setUp(): void
  {
    $this->elementFilter = new ElementFilter();
  }

  #[DataProvider('skipProvider')]
  public function testSkipTable(string $tableName, array $include, array $exclude, bool $expected): void
  {
    $table = $this->createStub(Table::class);
    $table->method('getName')->willReturn($tableName);

    $skipTable = $this->elementFilter->skipTable($table, $include, $exclude);
    $this->assertSame($expected, $skipTable);
  }

  #[DataProvider('skipProvider')]
  public function testSkipForeignKey(string $foreignTableName, array $include, array $exclude, bool $expected): void
  {
    $fk = $this->createStub(ForeignKeyConstraint::class);
    $fk->method('getForeignTableName')->willReturn($foreignTableName);

    $skipTable = $this->elementFilter->skipForeignKey($fk, $include, $exclude);
    $this->assertSame($expected, $skipTable);
  }

  #[DataProvider('skipProvider')]
  public function testSkipView(string $viewName, array $include, array $exclude, bool $expected): void
  {
    $view = $this->createStub(View::class);
    $view->method('getName')->willReturn($viewName);

    $skipTable = $this->elementFilter->skipView($view, $include, $exclude);
    $this->assertSame($expected, $skipTable);
  }

  public static function skipProvider(): array
  {
    return [
      ['', [], [], false],
      ['foo', [], [], false],
      // Include only
      ['foo', ['foo'], [], false],
      ['foo', ['foo', 'bar', 'baz'], [], false],
      ['foo', self::$tableSet1, [], true],
      ['foo', self::$tableSet2, [], true],
      ['public.users', self::$tableSet1, [], false],
      ['Public.users', self::$tableSet2, [], true],
      // Exclude only
      ['foo', [], ['foo'], true],
      ['foo', [], ['foo', 'bar', 'baz'], true],
      ['foo', [], self::$tableSet1, false],
      ['foo', [], self::$tableSet2, false],
      ['public.users', [], self::$tableSet1, true],
      ['Public.users', [], self::$tableSet2, false],
      // Exclude and include
      ['foo', ['foo', 'bar'], ['bar'], false],
      ['bar', ['foo', 'bar'], ['bar'], true],
      ['baz', ['foo', 'bar'], ['bar'], true],
      ['public.users', self::$tableSet1, self::$tableSet2, false],
    ];
  }
}
