async function links({ toElement: elementHtml }) {

    const route = new Route();
    const newHtmlContent = await route.fetchHtml();
    const source = `${elementHtml.getAttribute('source')}.html`;

    document.documentElement = newHtmlContent;
    window.history.pushState("", "FaceGif", source);
}

const linksInPage = document.querySelectorAll('links');
linksInPage.forEach(item => item.addEventListener('click', links));