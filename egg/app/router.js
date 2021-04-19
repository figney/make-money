'use strict';

/**
 * @param {Egg.Application} app - egg application
 */
module.exports = app => {
    const { router, controller, middleware, io } = app;

    const origin = middleware.origin();

    router.get('/', controller.home.index);

    router.post('/push/toAll', origin, controller.push.toAll)
    router.post('/push/toUser', origin, controller.push.toUser)

    io.of('/').route('init-user', io.controller.user.initUser)
};
