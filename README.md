# Minecraft Custom Skull Generator
The source code for http://crushedpixel.eu/skull, a web service that allows map makers to create custom skulls from a skin file.

## Introduction
Using the Custom Skull Generator, you can create Player Heads from any skin file you want. Simply link to a skin on a website (e.g. on Imgur) or upload a skin file from your computer.

This tool works with the newest Minecraft versions and is not affected by the Security Update which broke the old way of creating Custom Player Skulls.

### How it works
The skin files you provide are being uploaded to a Premium Minecraft Account. Using a call to the Mojang API, the system retrieves the URL of the skin file as well as the signature key which is required since the 1.8.4 Update.

Due to rate limitations on the Mojang API, a new security key can only be retrieved every 30 seconds. Therefore, multiple Minecraft Accounts are required to power this tool.

There are currently 7 Accounts being used, allowing the generation of a Custom Skull every 4.29 seconds.

If you have a spare Minecraft Account which we can use to speed up this website, please contact us at crushedpixelmaps@gmail.com
