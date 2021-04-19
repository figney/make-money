/* eslint valid-jsdoc: "off" */

'use strict';

/**
 * @param {Egg.EggAppInfo} appInfo app info
 */
module.exports = appInfo => {
    /**
     * built-in config
     * @type {Egg.EggAppConfig}
     **/
    const config = exports = {};
    config.security = {
        csrf: {
            enable: false,
            ignoreJSON: false,
            useSession: true, // 默认为 false，当设置为 true 时，将会把 csrf token 保存到 Session 中
            cookieName: 'csrfToken', // Cookie 中的字段名，默认为 csrfToken
            sessionName: 'csrfToken', // Session 中的字段名，默认为 csrfToken
        },
        domainWhiteList: ['*'],
    };
    config.io = {
        init: {}, // passed to engine.io
        namespace: {
            '/': {
                connectionMiddleware: [
                    'connection'
                ],
                packetMiddleware: [],
            },
        },
    };
    config.cors = {
        origin: '*',
        allowMethods: 'GET,HEAD,PUT,POST,DELETE,PATCH'
    };

    // use for cookie sign key, should change to your own and keep security
    config.keys = appInfo.name + '_1614249827252_3768';

    // add your middleware config here
    config.middleware = [];




    // add your user config here
    const userConfig = {
        // myAppName: 'egg',
    };


    return {
        ...config,
        ...userConfig,
    };
};
