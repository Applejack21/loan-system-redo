const defaultTheme = require("tailwindcss/defaultTheme");
const { darken, lighten } = require("polished");

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            screens: {
                xxs: "375px",
                // => @media (min-width: 375px) { ... }
                xs: "425px",
                // => @media (min-width: 425px) { ... }
                sm: "640px",
                // => @media (min-width: 640px) { ... }
                md: "768px",
                // => @media (min-width: 768px) { ... }
                lg: "1024px",
                // => @media (min-width: 1024px) { ... }
                xl: "1200px",
                // => @media (min-width: 1200px) { ... }
            },
            colors: {
                primary: {
                    light: lighten(0.1, "#2e6e9e"),
                    DEFAULT: "#2e6e9e",
                    dark: "#1d4563",
                },
                secondary: {
                    light: lighten(0.1, "#4d95cb"),
                    DEFAULT: "#4d95cb",
                    dark: "#326489",
                },
                success: {
                    light: lighten(0.1, "#34b339"),
                    DEFAULT: "#34b339",
                    dark: darken(0.1, "#34b339"),
                },
                error: {
                    light: lighten(0.1, "#c41a1a"),
                    DEFAULT: "#c41a1a",
                    dark: darken(0.1, "#c41a1a"),
                },
                warn: {
                    light: lighten(0.1, "#edba1a"),
                    DEFAULT: "#edba1a",
                    dark: darken(0.1, "#edba1a"),
                },
            },
            maxWidth: {
                "8xl": "1440px",
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio')
    ],
};