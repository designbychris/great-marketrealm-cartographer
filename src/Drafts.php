<?php
declare(strict_types=1);
namespace GreatMarketrealmCartographer;
defined('ABSPATH') || exit;
final class Drafts {
 public const TYPE='gmrcart_draft';
 public static function register():void {register_post_type(self::TYPE,['label'=>__('Cartographer drafts','great-marketrealm-cartographer'),'public'=>false,'show_ui'=>false,'show_in_rest'=>false,'supports'=>['title']]);}
 public static function allowed(int $user,string $campaign):bool {return $user>0 && $campaign!=='' && (bool)apply_filters('gmrcart_can_edit_campaign',false,$user,$campaign);}
 public static function create(int $user,string $campaign,string $name):int {
  if(!self::allowed($user,$campaign)||trim($name)==='')return 0;
  $id=wp_insert_post(['post_type'=>self::TYPE,'post_status'=>'private','post_author'=>$user,'post_title'=>sanitize_text_field($name)],true);
  if(is_wp_error($id)||!$id)return 0;
  update_post_meta((int)$id,'_gmrcart_campaign_id',sanitize_text_field($campaign));
  update_post_meta((int)$id,'_gmrcart_schema_version',1);
  return (int)$id;
 }
 /** Campaign metadata stays within the draft repository boundary. */
 public static function campaignId(int $draftId):string {
  return $draftId > 0 ? (string)get_post_meta($draftId,'_gmrcart_campaign_id',true) : '';
 }
 public static function owned(int $user):array {
  if($user<1)return [];
  $posts=get_posts(['post_type'=>self::TYPE,'post_status'=>'private','author'=>$user,'numberposts'=>100]);
  return array_values(array_filter($posts,static fn($p):bool=>self::allowed($user,(string)get_post_meta((int)$p->ID,'_gmrcart_campaign_id',true))));
 }
}
