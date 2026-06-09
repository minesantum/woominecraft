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