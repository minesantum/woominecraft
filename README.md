# WooMinecraft
### By DonKolia

**WooMinecraft** is a powerful bridge solution that connects your **WooCommerce** store directly with your **Minecraft** server. It facilitates automatic donation processing and introduces a seamless in-game shopping experience for your players.

## 🚀 Key Features

*   ** automated Command Execution**: When a player makes a purchase on your website, the plugin automatically executes the corresponding console commands in the server (e.g., giving items, ranks, or money).
*   **In-Game GUI Shop**: Players can browse your web store directly from Minecraft using the `/woo shop` command.
    *   **Visual Categories**: Categories are displayed as items in a chest interface.
    *   **Product Details**: Clicking a category reveals its products, complete with names, prices, and descriptions fetched directly from WooCommerce.
*   **Safety Checks**:
    *   **Inventory Full Protection**: If a player's inventory is full, the plugin will pause the delivery of their purchase and notify them to clear space, ensuring no items are lost.
*   **Secure Connection**: Uses a secure API Key system to communicate between WordPress and the Minecraft server.

---

## 📦 Components

This system consists of two parts that work together:

### 1. The WordPress Plugin
This plugin installs on your WordPress site. It creates the necessary API endpoints to:
*   Send pending orders to the server.
*   Serve product and category data for the in-game shop.

### 2. The Minecraft Plugin
This Java plugin runs on your Spigot/Paper server. It:
*   Periodically checks the website for new orders.
*   Provides the `/woo` commands and the graphic interface for the shop.

---

## 🛠️ Installation

### WordPress Side
1.  Upload the `woominecraft` folder (or zip) to your `/wp-content/plugins/` directory.
2.  Activate the plugin in your WordPress Admin Dashboard.
3.  Go to **WooCommerce > Settings > WooMinecraft** (or the designated settings page) to generate your **Server Key**.

### Minecraft Side
1.  Place the `WooMinecraft.jar` file into your server's `plugins` folder.
2.  Restart the server to generate the default `config.yml`.
3.  Edit `plugins/WooMinecraft/config.yml`:
    *   **url**: Set this to your homepage URL (e.g., `http://example.com`).
    *   **key**: Paste the Server Key generated in the WordPress plugin.
4.  Run `/woo help` or restart the server to verify the connection.

---

## 📜 Commands & Permissions

| Command | Permission | Description |
| :--- | :--- | :--- |
| `/woo shop` | `woo.use` | Opens the in-game GUI Shop to browse categories and products. |
| `/woo check` | `woo.admin` | Manually checks for new pending donations/orders immediately. |
| `/woo ping` | `woo.admin` | Tests the connection to your WordPress site. |
| `/woo debug` | `woo.admin` | Toggles debug mode for detailed logging in the console. |
| `/woo help` | `woo.admin` | Shows the help menu. |

---

## ⚙️ Configuration (config.yml)

```yaml
# The URL to your WordPress installation
url: http://your-store-url.com

# The API Key from the WordPress Plugin settings
key: YOUR_SECRET_KEY_HERE

# How often (in seconds) to check for new donations
update_interval: 90

# Enable debug mode for troubleshooting
debug: false
```

---

*Developed by DonKolia*
