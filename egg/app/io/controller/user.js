'use strict';

const Controller = require('egg').Controller;

class UserController extends Controller {
    async initUser() {
        const {app, ctx} = this
        let userID = ctx.args[0] || ''
        ctx.socket.join(userID)
        return
    }

    async outUser() {
        const {ctx} = this
        let userID = ctx.args[0] || ''
        ctx.socket.leave(userID)
        return
    }
}

module.exports = UserController;
