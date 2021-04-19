'use strict';

const Controller = require('egg').Controller;

class PushController extends Controller {
    async toAll() {
        const {ctx, app} = this;
        const {event, data} = await ctx.request.body;
        app.io.emit(event || 'event', data);
        ctx.body = 'success';

    }

    async toUser() {
        const {ctx, app} = this;
        const {event, id, data} = await ctx.request.body;
        data.u = 1;
        app.io.of('/')
            .to(id)
            .emit(event || 'event', data);
        ctx.body = 'success';

    }
}

module.exports = PushController;
