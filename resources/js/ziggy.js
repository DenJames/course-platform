const Ziggy = {"url":"http:\/\/course-platform.test","port":null,"defaults":{},"routes":{"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"welcome":{"uri":"\/","methods":["GET","HEAD"]},"courses.show":{"uri":"courses\/{course}","methods":["GET","HEAD"],"parameters":["course"],"bindings":{"course":"id"}},"courses.category":{"uri":"category\/{category}","methods":["GET","HEAD"],"parameters":["category"],"bindings":{"category":"slug"}},"cart":{"uri":"cart","methods":["GET","HEAD"]},"checkout":{"uri":"checkout","methods":["GET","HEAD"]},"process.purchases":{"uri":"checkout\/process","methods":["POST"]},"profile.edit":{"uri":"profile","methods":["GET","HEAD"]},"profile.update":{"uri":"profile","methods":["PATCH"]},"profile.destroy":{"uri":"profile","methods":["DELETE"]},"owned.courses.index":{"uri":"my-courses","methods":["GET","HEAD"]},"reviews.update":{"uri":"courses\/{course}\/reviews","methods":["POST"],"parameters":["course"],"bindings":{"course":"id"}},"reviews.destroy":{"uri":"courses\/{course}\/reviews","methods":["DELETE"],"parameters":["course"],"bindings":{"course":"id"}},"lessons.toggle-completion":{"uri":"lessons\/{lesson}\/toggle-completion","methods":["POST"],"parameters":["lesson"],"bindings":{"lesson":"id"}},"courses.reset-progress":{"uri":"courses\/{course}\/reset-progress","methods":["POST"],"parameters":["course"],"bindings":{"course":"id"}},"courses.mark-complete":{"uri":"courses\/{course}\/mark-complete","methods":["POST"],"parameters":["course"],"bindings":{"course":"id"}},"password.create":{"uri":"password\/create\/{user}","methods":["GET","HEAD"],"parameters":["user"],"bindings":{"user":"id"}},"store.password":{"uri":"password\/store\/{user}","methods":["POST"],"parameters":["user"],"bindings":{"user":"id"}},"register":{"uri":"register","methods":["GET","HEAD"]},"login":{"uri":"login","methods":["GET","HEAD"]},"password.request":{"uri":"forgot-password","methods":["GET","HEAD"]},"password.email":{"uri":"forgot-password","methods":["POST"]},"password.reset":{"uri":"reset-password\/{token}","methods":["GET","HEAD"],"parameters":["token"]},"password.store":{"uri":"reset-password","methods":["POST"]},"verification.notice":{"uri":"verify-email","methods":["GET","HEAD"]},"verification.verify":{"uri":"verify-email\/{id}\/{hash}","methods":["GET","HEAD"],"parameters":["id","hash"]},"verification.send":{"uri":"email\/verification-notification","methods":["POST"]},"password.confirm":{"uri":"confirm-password","methods":["GET","HEAD"]},"password.update":{"uri":"password","methods":["PUT"]},"logout":{"uri":"logout","methods":["POST"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };