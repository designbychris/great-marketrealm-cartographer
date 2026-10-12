<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use GreatMarketrealmCartographer\Drafts;
final class DraftsFoundationTest extends TestCase {
 public function testDraftTypeIsPrivate():void {
  self::assertSame('gmrcart_draft',Drafts::TYPE);
  $s=file_get_contents(dirname(__DIR__).'/src/Drafts.php');
  self::assertStringContainsString("'public'=>false",$s);
  self::assertStringContainsString("'post_author'",$s);
 }
 public function testInvalidCampaignAccessFailsClosed():void {
  self::assertFalse(Drafts::allowed(0,'abc'));
  self::assertFalse(Drafts::allowed(2,''));
 }
 public function testDraftCreationIsNonceProtected():void {
  $s=file_get_contents(dirname(__DIR__).'/src/Workshop.php');
  self::assertStringContainsString("check_admin_referer('gmrcart_create_draft')",$s);
  self::assertStringContainsString("'gmrcart_keeper_entitled'",$s);
 }
}
