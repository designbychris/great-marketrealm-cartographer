# Phase VI.4A.4 — The Workshop Finds Its Home

Create a **published WordPress page** with slug `cartographer` (URL `/cartographer/`). In Elementor, insert a **Shortcode** widget containing `[gmrcart_workshop]`.

The plugin detects the published page automatically and generates campaign-aware links to it. The existing `/?gmrcart_workshop=1` entry point redirects to the new page; until the page exists it continues to render the legacy theme-wrapped Workshop. Draft creation returns to the chosen page with the campaign and success flag preserved.

The shortcode outputs only the Workshop's markup and scoped styles, leaving the WordPress theme/Elementor responsible for page header and footer. All campaign permissions and nonce validation remain server-side. Admin Workshop remains a fallback.

Install these files in the **Cartographer plugin only**. The Companion's existing bridge calls `gmrcart_workshop_url()` dynamically, so no Companion patch is required.

Test both the published page and the link from Companion; create a draft and confirm it persists after refresh. PHPUnit suite must be run on the server.
