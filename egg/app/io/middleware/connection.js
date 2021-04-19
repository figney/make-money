module.exports = app => {
    return async (ctx, next) => {
        console.log('connected!',ctx.socket.id);
        //ctx.socket.emit('res', 'connected!');
        app.allUserNum++;
        await next();
        app.allUserNum--;
        // execute when disconnect.
        console.log('disconnection!');
    };
};
