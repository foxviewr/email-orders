module.exports = {
    reactStrictMode: true,
    env: {
        BACKEND_URL: process.env.BACKEND_URL,
        IS_DOCKER: process.env.IS_DOCKER,
        MAIL_FROM_ADDRESS: process.env.MAIL_FROM_ADDRESS,
        MAIL_FROM_NAME: process.env.MAIL_FROM_NAME,
    }
}
