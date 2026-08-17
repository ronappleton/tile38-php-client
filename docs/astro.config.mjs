import { defineConfig } from "astro/config";
import starlight from "@astrojs/starlight";

export default defineConfig({
  site: "https://ronappleton.github.io/tile38-php-client/",
  integrations: [
    starlight({
      title: "Tile38 PHP Client",
      description:
        "A PHP client for the Tile38 ultra-fast geospatial database and realtime geofencing server.",
      logo: {
        src: "./src/assets/logo.svg",
        alt: "Tile38 PHP Client logo",
      },
      customCss: ["./src/styles/custom.css"],
      social: [
        {
          icon: "github",
          label: "GitHub",
          href: "https://github.com/ronappleton/tile38-php-client",
        },
      ],
      editLink: {
        baseUrl:
          "https://github.com/ronappleton/tile38-php-client/edit/master/docs/",
      },
      components: {
        Hero: "./src/components/Hero.astro",
        PageFrame: "./src/components/PageFrame.astro",
      },
      sidebar: [
        {
          label: "Getting Started",
          items: [
            { label: "Installation", link: "/getting-started/installation/" },
            { label: "Quick Start", link: "/getting-started/quick-start/" },
            { label: "Configuration", link: "/getting-started/configuration/" },
          ],
        },
        {
          label: "Concepts",
          items: [
            { label: "The Fluent API", link: "/concepts/fluent-api/" },
            { label: "Object Types", link: "/concepts/object-types/" },
            { label: "Search", link: "/concepts/search/" },
            { label: "Output Formats", link: "/concepts/output-formats/" },
            { label: "Timeouts", link: "/concepts/timeout/" },
          ],
        },
        {
          label: "Reference",
          items: [
            { label: "Command Reference", link: "/reference/commands/" },
            {
              label: "Version Compatibility",
              link: "/reference/version-compatibility/",
            },
          ],
        },
        {
          label: "Guides",
          items: [
            { label: "Geofencing & Channels", link: "/guides/geofencing/" },
            { label: "Webhooks", link: "/guides/webhooks/" },
            { label: "Lua Scripting", link: "/guides/scripting/" },
          ],
        },
        {
          label: "Tutorials",
          items: [
            { label: "Track a Fleet", link: "/tutorials/fleet-tracking/" },
            { label: "Geofence Alerts", link: "/tutorials/geofence-alerts/" },
            { label: "Store Locator", link: "/tutorials/store-locator/" },
            { label: "Asset Check-In", link: "/tutorials/asset-checkin/" },
            { label: "Postcode Lookup", link: "/tutorials/postcode-lookup/" },
            { label: "Delivery Radius", link: "/tutorials/delivery-radius/" },
            { label: "Realtime Dispatch", link: "/tutorials/driver-dispatch/" },
            { label: "IoT Roaming Geofences", link: "/tutorials/iot-roaming/" },
            { label: "Real Estate Search", link: "/tutorials/real-estate/" },
          ],
        },
      ],
    }),
  ],
});
