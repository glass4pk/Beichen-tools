<script>
/**
 * 全局变量
 */
const WEB_URL_PROTOCOL = 'http'
const DEV_DOMAIN = '127.0.0.1'
const PRODUCT_DOMAIN = 'dev.yes-go.cn'
const WEB_DOMAIN = process.env.NODE_ENV === 'development' ? DEV_DOMAIN : PRODUCT_DOMAIN
const WEB_URL = WEB_URL_PROTOCOL + '://' + WEB_DOMAIN
export default {
  WEB_URL_PROTOCOL,
  WEB_DOMAIN,
  WEB_URL
}
</script>
