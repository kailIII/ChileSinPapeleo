new TWTR.Widget({
    version: 2,
    type: 'search',
    search: 'chilesinpapeleo',
    interval: 5000,
    title: 'Siguenos en Twitter',
    subject: '@ChileSinPapeleo',
    width: 'auto',
    height: 300,
    theme: {
        shell: {
            background: '#007100',
            color: '#ffffff'
        },
        tweets: {
            background: '#ffffff',
            color: '#444444',
            links: '#006000'
        }
    },
    features: {
        scrollbar: false,
        loop: true,
        live: true,
        behavior: 'default'
    }
}).render().start();