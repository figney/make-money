'use strict';
// 解决跨域的中间件
module.exports = options => {
  return async function setOrigin(ctx, next) {
    ctx.response.set('Access-Control-Allow-Origin', '*');
    ctx.response.set('Access-Control-Allow-Credentials', true);
    ctx.response.set('Access-Control-Allow-Headers', 'x-requested-with, authorization, Content-Type, Authorization, credential, X-XSRF-TOKEN,token,username,client');
    ctx.response.set('Access-Control-Allow-Methods', '*');
    ctx.response.set('Access-Control-Expose-Headers', '*');
    ctx.response.set('Access-Control-Max-Age', 180000);

    await next();
  };
};
