<div id="root">

    <div class="menu">
        <a class="item active">
            <i class="fas fa-home"></i>
            Feed
        </a>
        <a class="item" data-item-menu="followers">
            <i class="fas fa-user-friends"></i>
            Seguidores
        </a>
        <a href="/logout" class="item">
            <i class="fas fa-door-open"></i>
            Sair
        </a>
    </div>



    <div class="feed">
        <div class="publication">
            <img src="<?= $_SESSION['user']['avatar'] ?>" class="avatar" alt="avatar do usuário" onerror="this.src='./img/avatar_default.png'" />
            <div class="share">
                <form action="/publications?api">
                    <textarea name="publish_desc" id="publish_desc" placeholder="Publique algo"></textarea>
                    <input type="file" id="publish_image" name="images[]" accept="image/png, image/jpeg" style="display: none;">
                </form>
                <script>
                    publish_desc.addEventListener('keyup', () => {
                        const lengthCaracteres = publish_desc.value.length;
                        const newFontSize = 2 - 0.1 * lengthCaracteres;
                        const limitToRedimension = 10;

                        if (lengthCaracteres < limitToRedimension)
                            publish_desc.style.fontSize = `${newFontSize}rem`;
                    });
                </script>
                <div class="preview"></div>
                <div class="actions">
                    <div class="btn-actions">
                        <button class="publish-image">
                            <i class="far fa-image"></i>
                        </button>
                        <button><i class="fas fa-video"></i></button>
                        <button><i class="fab fa-youtube"></i></button>
                        <button><i class="far fa-smile-beam"></i></button>
                    </div>
                    <button class="btn-publish">
                        <i class="fas fa-paper-plane" style="margin-right: 5px; margin-bottom: -3px;"></i>
                        Postar
                    </button>
                </div>
            </div>
        </div>
        <div class="cards"></div>
    </div>



    <div class="aside">
        <div class="box">
            <header>
                <h2>Sugestões para seguir</h2>
            </header>
            <div class="content sugestions"></div>
        </div>
    </div>
</div>



<div class="followers" style="display: none">
    <i class="fas fa-times"></i>
    <div class="box">
        <header>
            <nav>
                <button class="link active">#meus_seguidores</button>
            </nav>
            <div class="input-search">
                <input type="search" name="search" placeholder="Pesquise por alguém...">
            </div>
        </header>
        <main></main>
    </div>
</div>