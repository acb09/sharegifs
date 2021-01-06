function Route() {

    this.route = window.location.pathname;
    this.routes = [
        'login',
        'register',
        'feed',
        'notFound'
    ];

}

Route.prototype.getRoute = () => {
    const routeEmpty = this.route === "/";
    const routeAllowed = routes.includes(this.route) || routeEmpty;

    if (routeAllowed)
        return this.route;
};

Route.prototype.fetchHtml = async () => {
    route = Route.getRoute();
    console.log(this.route);
    const request = await fetch(this.route);
    const responseHtml = await request.text();

    return responseHtml
}