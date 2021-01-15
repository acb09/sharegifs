function scrollPage(e) {
    const { height } = window.screen;
    window.scrollTo({
        top: height + height,
        behavior: 'smooth'
    });
};





function createPublicationHtml(publish) {
    // const comments = document.createElement('div');
    // comments.classList.add(''); 

    const iShare = document.createElement('i');
    iShare.style.marginRight = '5px';
    iShare.classList.add('fas');
    iShare.classList.add('fa-retweet');

    const iComment = document.createElement('i');
    iComment.style.marginRight = '5px';
    iComment.classList.add('far');
    iComment.classList.add('fa-comment');

    const iHeart = document.createElement('i');
    iHeart.dataset.isHeart = publish.hearts.isHeart;
    iHeart.style.marginRight = '5px';
    iHeart.classList.add(publish.hearts.isHeart == 'true' ? 'fas' : 'far');
    iHeart.classList.add('fa-heart');
    iHeart.style.color = publish.hearts.isHeart == 'true' ? 'red' : '';
    iHeart.addEventListener('click', heart);

    const spanAmountComments = document.createElement('span');
    spanAmountComments.textContent = formatNumber(publish.comments.amount);

    const spanAmountHearts = document.createElement('span');
    spanAmountHearts.textContent = formatNumber(publish.hearts.amount);

    const spanAmountShares = document.createElement('span');
    spanAmountShares.textContent = formatNumber(publish.shares.amount);

    const actionsItemComment = document.createElement('button');
    actionsItemComment.appendChild(iComment);
    actionsItemComment.appendChild(spanAmountComments);

    const actionsItemHearts = document.createElement('button');
    actionsItemHearts.appendChild(iHeart);
    actionsItemHearts.appendChild(spanAmountHearts);

    const actionsItemShares = document.createElement('button');
    actionsItemShares.appendChild(iShare);
    actionsItemShares.appendChild(spanAmountShares);
    actionsItemShares.addEventListener('click', share);

    const actions = document.createElement('div');
    actions.classList.add('actions');
    actions.appendChild(actionsItemHearts);
    actions.appendChild(actionsItemComment);
    actions.appendChild(actionsItemShares);

    const aPlink = document.createElement('a');
    aPlink.href = publish.profile.link;
    aPlink.innerHTML = publish.profile.link;

    const iPlink = document.createElement('i');
    iPlink.style.marginRight = '5px';
    iPlink.classList.add('fas');
    iPlink.classList.add('fa-link');

    const pLink = document.createElement('p');
    pLink.appendChild(iPlink);
    pLink.appendChild(aPlink);

    const legend = document.createElement('legend');
    legend.textContent = publish.legend;
    legend.appendChild(pLink);

    const image = document.createElement('img');
    const divCard = document.createElement('div');

    if (publish.id_original) {

        fetch('/publications?api&id=' + publish.id_original)
            .then(request => request.json())
            .then(response => {

                if (response.status != 404)
                    divCard.appendChild(createPublicationHtml(response));

            });

    } else {

        image.src = publish.image;
        image.width = 600;
        image.onerror = function () {
            this.parentElement.parentElement.querySelector('.image').innerHTML = '';
        }

        divCard.classList.add('image');
        divCard.appendChild(image);

        (publish.link) ? divCard.appendChild(legend) : '';

    }


    const description = document.createElement('div');
    description.classList.add('description');

    if (publish.description)
        description.innerHTML = publish.description.trim().replace(/(http.?:\/\/.[^\s]*)/g, '<a target="_blank" href="$1">$1</a>');

    const avatar = document.createElement('img');
    avatar.src = publish.profile.avatar;

    const btnProfileFollow = document.createElement('button');
    btnProfileFollow.classList.add('follow');
    btnProfileFollow.dataset.profileId = publish.profile.id;
    btnProfileFollow.dataset.follow = publish.profile.follow;
    btnProfileFollow.innerHTML = publish.profile.follow ? 'Seguindo' : '<i class="fas fa-plus"></i> Seguir'
    btnProfileFollow.addEventListener('click', followUnfollow);

    const profileName = document.createElement('h4');
    profileName.textContent = publish.profile.name;

    const profile = document.createElement('div');
    profile.classList.add('profile');
    profile.dataset.id = publish.profile.id;
    profile.appendChild(profileName);
    profile.appendChild(btnProfileFollow);

    const content = document.createElement('div');
    content.classList.add('content');
    content.appendChild(profile);
    content.appendChild(description);
    content.appendChild(divCard);
    content.appendChild(actions);
    // content.appendChild(comments);

    const card = document.createElement('div');
    card.classList.add('card');
    card.dataset.id = publish.id;
    card.appendChild(avatar);
    card.appendChild(content);

    return card;
}





function addPublication(publish) {
    return document.querySelector('.cards').prepend(createPublicationHtml(publish)) ?? false;
}





function addSuggestion({ profile }) {

    const btnFollow = document.createElement('button');
    btnFollow.classList.add('follow');
    btnFollow.dataset.profileId = profile.id;
    btnFollow.dataset.follow = profile.follow;
    btnFollow.innerHTML = profile.follow ? 'Seguindo' : '<i class="fas fa-plus"></i> Seguir'
    btnFollow.addEventListener('click', followUnfollow);

    const follow = document.createElement('span');
    follow.classList.add('amount_follow');
    follow.textContent = formatNumber(profile.amount_followers).concat((profile.amount_followers > 1) ? ' seguidores ' : ' seguidor ');

    const name = document.createElement('span');
    name.textContent = profile.name;

    const info = document.createElement('div');
    info.appendChild(name);
    info.appendChild(follow);

    const imgAvatar = document.createElement('img');
    imgAvatar.src = profile.avatar;

    const avatar = document.createElement('div');
    avatar.classList.add('avatar');
    avatar.appendChild(imgAvatar);

    const user = document.createElement('div');
    user.classList.add('info');
    user.appendChild(avatar);
    user.appendChild(info);

    const sugestion = document.createElement('div');
    sugestion.classList.add('profile');
    sugestion.dataset.id = profile.id;
    sugestion.appendChild(user);
    sugestion.appendChild(btnFollow);

    const sugestions = document.querySelector('.sugestions');
    if (sugestions)
        sugestions.appendChild(sugestion);
}






function addFollowers(profile) {
    const iBtnFollower = document.createElement('i');
    iBtnFollower.classList.add('fas');
    iBtnFollower.classList.add('fa-plus');

    const btnFollower = document.createElement('button');
    btnFollower.classList.add('follow');
    btnFollower.dataset.follow = profile.follow;
    btnFollower.dataset.profileId = profile.id;
    if (profile.follow)
        btnFollower.append(' Seguindo ');
    else {
        btnFollower.appendChild(iBtnFollower);
        btnFollower.append(' Seguir');
    }
    btnFollower.addEventListener('click', followUnfollow);

    const spanFollower = document.createElement('span');
    spanFollower.classList.add('amount_follow');
    spanFollower.append(formatNumber(profile.amount_followers).concat((profile.amount_followers > 1) ? ' seguidores ' : ' seguidor '));

    const spanName = document.createElement('span');
    spanName.append(profile.name);

    const divInfo = document.createElement('div');
    divInfo.classList.add('info');
    divInfo.appendChild(spanName);
    divInfo.appendChild(spanFollower);

    const avatar = document.createElement('img');
    avatar.src = profile.avatar;
    avatar.classList.add('avatar');

    const a = document.createElement('a');
    a.appendChild(avatar);
    a.appendChild(divInfo);

    const newProfile = document.createElement('div');
    newProfile.classList.add('profile');
    newProfile.classList.add('user');
    newProfile.dataset.id = profile.id;
    newProfile.appendChild(a);
    newProfile.appendChild(btnFollower);

    const followersList = document.querySelector('.followers > .box > main');

    if (followersList)
        followersList.appendChild(newProfile);
}






async function addFollowersSearch() {
    const followersField = document.querySelector('.followers .box > main');

    followersField.innerHTML = '';

    await fetchProfiles()
        .then(profiles => {
            profiles.forEach(addFollowers);
        });
}






function cleanSugestion() {
    const sugestions = document.querySelectorAll('.sugestions .profile');
    if (sugestions)
        sugestions.forEach(profile => profile.remove());
}






async function followUnfollow({ target }) {
    const id = target.dataset.profileId;
    const follow = target.dataset.follow == 'true';
    const url = follow ? `/unfollow?api&id_followed=${id}` : `/follow?api&id_followed=${id}`;
    const profilesMirror = document.querySelectorAll(`.profile[data-id="${id}"]`);
    const request = await fetch(url);
    const response = await request.json();

    if (response.status >= 200 && response.status < 300) {
        target.dataset.follow = !follow;
        target.innerHTML = !follow ? 'Seguindo' : '<i class="fas fa-plus"></i> Seguir';
        profilesMirror.forEach(profile => {
            const spanFollow = profile.querySelector('.amount_follow');
            const amount = Number(spanFollow.textContent.match(/\d/).shift());
            if (follow) {
                spanFollow.innerHTML = formatNumber(amount - 1);
                spanFollow.innerHTML += ((amount - 1 > 1) ? ' seguidores ' : ' seguidor ');
            } else {
                spanFollow.innerHTML = formatNumber(amount + 1);
                spanFollow.innerHTML += ((amount + 1 > 1) ? ' seguidores ' : ' seguidor ');
            }
        });
    }
}





async function share({ target }) {

    const divCard = target.parentElement.parentElement.parentElement.parentElement.querySelector('.card') ?? target.parentElement.parentElement.parentElement.parentElement;
    const divProfile = divCard.querySelector('.profile[data-id]');
    // const divDescription = divCard.querySelector('.description');
    // const divImage = divCard.querySelector('.image img');

    const id_owned = divProfile.dataset.id;
    const id_original = divCard.dataset.id;
    // const description = divDescription.textContent;
    // const image = divImage.src;

    const publish = {};
    publish.id_owned = id_owned;
    publish.id_original = id_original;
    // publish.description = '';
    // publish.image = image;

    const data = new FormData();

    // data.append('description', description);
    data.append('id_original', id_original);
    data.append('id_owned', id_owned);
    // data.append('images[]', image);

    const metaData = {};
    metaData.method = 'POST';
    metaData.body = data;

    const request = await fetch('/publications?api&shares', metaData);
    const response = await request.json();

    const requestShare = await fetch(`/publications?api&id=${response.publish.id_original}`);
    const responseShare = await requestShare.json();

    response.publish.card = responseShare;

    addPublication(response.publish);
}





async function heart({ target }) {

    const isHeart = target.dataset.isHeart == "true";
    const id = target.parentElement.parentElement.parentElement.parentElement.dataset.id;
    const spanAmount = target.parentElement.querySelector('span');

    const url = isHeart === false ? `/heart?api&id=${id}` : `/heart?api&id=${id}&not`;
    const request = await fetch(url);
    const { status, amount_hearts } = await request.json();

    if (status >= 200 & status <= 299) {
        if (!isHeart) {
            target.style.color = 'red';
            target.classList.replace('far', 'fas')
        } else {
            target.style.color = '#222';
            target.classList.replace('fas', 'far');
        }
        target.dataset.isHeart = !isHeart;
        spanAmount.textContent = amount_hearts;
    }
}





async function uploadAvatar() {
    const file = document.querySelector('input[type=file][name=avatar]').files[0];
    if (!file)
        throw new exception("Imagem não carregada!");

    const data = new FormData();
    data.append('avatar', file);

    const fetchRequest = {};
    fetchRequest.method = "POST";
    fetchRequest.body = data;

    const request = await fetch('/avatar?api', fetchRequest);
    const { status, avatar } = await request.json();

    if (status >= 200 && status < 290)
        document.querySelector('img.avatar').src = avatar;
}





function formatNumber(number) {
    const isThousand = number >= 1000 && number < 1000000;
    const isMillion = number >= 1000000 && number < 1000000000;
    const isBillion = number >= 1000000000 && number < 1000000000000;

    if (isThousand)
        return (number / 1000).toFixed(0) + 'K';
    else if (isMillion)
        return (number / 1000000).toFixed(0) + 'M';
    else if (isBillion)
        return (number / 100000000).toFixed(0) + 'B';
    else
        return number;
}






function openCloseFollowers() {
    const followersModal = document.querySelector('.followers');
    const isHide = followersModal.style.display == 'none';

    return followersModal.style.display = isHide ? 'block' : 'none';
}






async function fetchSugestion() {
    const request = await fetch('/sugestions?api');
    const response = await request.json();

    return response;
}






async function fetchFollowers() {
    const request = await fetch('/followers?api');
    const response = await request.json();

    return response;
}






async function fetchProfiles() {
    const name = document.querySelector('input[name=search]').value;
    const request = await fetch('/search?api&name=' + name);
    const response = await request.json();

    return response;
}





async function fetchPublications() {
    const request = await fetch('/publications?api');
    const response = await request.json();

    return response;
}






async function publish() {

    if (!publish_image || !publish_image.value && !publish_desc || !publish_desc.value)
        return false;

    const { files } = publish_image;
    const data = new FormData();
    const metaData = {};
    const description = publish_desc.value;

    if (files.length)
        for (const file of files)
            data.append('images[]', file, file.name);
    else {
        try {
            const urlFound = description.match(/http.?:\/\/.[^\s]*/);
            data.append('images[]', Array.isArray(urlFound) ? urlFound[0].trim() : '');
        } catch (error) {
            console.error(error.message);
        }
    }

    data.append('description', description);

    metaData.method = 'POST';
    metaData.body = data;

    const request = await fetch('/publications?api', metaData);
    const response = await request.json();

    addPublication(JSON.parse(response.publish));
    publish_desc.value = '';

    document.querySelector('.preview').innerHTML = '';
}





window.onload = function () {

    const existFeed = document.querySelector('.cards');
    const existSugestions = document.querySelector('.sugestions');
    const existFollowers = document.querySelector('.followers .box > main');
    const search = document.querySelector('input[name=search]');
    const btnScrollDown = document.querySelector('.btn-circle');
    const iconImage = document.querySelector('.publish-image');
    const inputImagePublish = iconImage ? publish_image : null;
    const followersTimes = document.querySelector('.followers > i.fa-times');
    const navBtnFollowers = document.querySelector('a[data-item-menu=followers]');
    const btnPublish = document.querySelector('.btn-publish');
    const imgAvatar = document.querySelector('.avatar');
    const inputAvatar = document.querySelector('input[name=avatar]');
    var sugestion = [];
    var followers = [];
    var feeds = [];

    if (imgAvatar && inputAvatar) {
        imgAvatar.addEventListener('click', () => inputAvatar.click());
        inputAvatar.addEventListener('change', uploadAvatar);
    }


    if (btnPublish)
        btnPublish.addEventListener('click', publish);

    if (existFeed)
        fetchPublications().then(publications => {
            publications.forEach(addPublication);
        });

    if (existSugestions)
        fetchSugestion().then(profiles => {
            sugestion = profiles;
            sugestion.forEach(addSuggestion);
        });

    if (existFollowers)
        fetchFollowers().then(profiles => {
            followers = profiles;
            followers.forEach(addFollowers);
        });

    if (search)
        search.addEventListener('keyup', addFollowersSearch);

    if (followersTimes && navBtnFollowers) {
        followersTimes.addEventListener('click', openCloseFollowers);
        navBtnFollowers.addEventListener('click', openCloseFollowers);
    }

    if (btnScrollDown) {
        btnScrollDown.addEventListener('click', scrollPage);
        window.addEventListener('onscroll', scrollPage);
    }

    if (inputImagePublish && iconImage) {
        iconImage.addEventListener('click', () => inputImagePublish.click());
        inputImagePublish.addEventListener('change', function () {
            Array.from(this.files).forEach(file => {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);

                URL.revokeObjectURL(img.src);

                const divPreview = document.querySelector('.preview');
                divPreview.innerHTML = ''; // Remover para múltiplos arquivos
                divPreview.appendChild(img);
                document.querySelector('.preview').style.display = "flex";
            });
        });
    }

    if (existFeed) {
        feeds.forEach(addPublication);
        feeds = [];
    }

};


