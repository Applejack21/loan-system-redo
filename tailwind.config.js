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
                    light: "#003F7F",
                    DEFAULT: "#001F3F",
                    dark: "#000F1F",
                },
                secondary: {
                    light: "#6AA9D5",
                    DEFAULT: "#3D85C6",
                    dark: "#3570A3",
                },
				accent: {
					light: "#FF8E66",
					DEFAULT: "#FF5733",
					dark: "#D1401F",
				},
				neutral: {
					white: "#FFFFFF",
					"light-grey": "#F5F5F5",
					"dark-grey": "#333333",
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
        require('@tailwindcss/aspect-ratio'),
		require('@headlessui/tailwindcss')({ prefix: 'ui' })
    ],
};
