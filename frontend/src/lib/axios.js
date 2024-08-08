import Axios from 'axios'

const ExecutionEnvironment = require('exenv')

let baseUrl = process.env.BACKEND_URL

if (!ExecutionEnvironment.canUseDOM) {
    const baseUrlHostName = (new URL(baseUrl)).hostname
    const isDocker = process.env.IS_DOCKER

    switch (true) {
        case isDocker === '1':
        case isDocker === 1:
        case isDocker === 'true':
        case isDocker === true:
            baseUrl = baseUrl.replace(baseUrlHostName, 'host.docker.internal')
            break
    }
}

const axios = Axios.create({
    baseURL: baseUrl,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
    withCredentials: true,
    withXSRFToken: true
})

export default axios
