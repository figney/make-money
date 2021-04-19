'use strict';

class AppBootHook {
	constructor(app) {
		this.app = app;
	}

	async configWillLoad() {
		process.env.UV_THREADPOOL_SIZE = 64;
	}

	async configDidLoad() {
		global.contractAddress = "";
	
	}

	async didLoad() {
		// All files have loaded, start plugin here.
		const { app } = this;
		app.regUserNum=0;
		app.allUserNum=0;
	}

	async willReady() {
		const { app, ctx } = this;
	}

	async didReady() {
	}

	async serverDidReady() {
		// Server is listening.
		const { app } = this;
	
	}

	async beforeClose() {
		// Do some thing before app close.
	}
}

module.exports = AppBootHook;
