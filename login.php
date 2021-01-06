<div class="column justify-center">
    <div class="description-login">
        <h2>Um novo jeito de compartilhar GIFS animados</h2>
        <p class="muted">Venha fazer parte deste mundo.</p>
        <button class="btn-circle">
            <i class="fas fa-chevron-down" style="font-size: 1.5rem"></i>
        </button>
    </div>
</div>
<div class="column justify-center">
    <div class="box-login">
        <h3>Faça seu login ou cadastra-se <a href="/register">clicando aqui</a></h3>
        <div class="inputs">
            <div class="input-group">
                <label id="labelEmail" for="email">Digite seu email</label>
                <input type="email" id="email" name="email" autocomplete="off">
                <script>
                    email.addEventListener('focus', () => labelEmail.classList.add('active'));
                    email.addEventListener('focusout', () => {
                        if (email.value.length == 0) labelEmail.classList.remove('active')
                    });
                </script>
            </div>
            <div class="input-group">
                <label id="labelPassword" for="password">Digite seu password</label>
                <input type="password" id="password" name="password">
                <script>
                    password.addEventListener('focus', () => labelPassword.classList.add('active'));
                    password.addEventListener('focusout', () => {
                        if (password.value.length == 0) labelPassword.classList.remove('active')
                    });
                </script>
            </div>
            <div class="input-group">
                <input type="submit" id="btnLogin" class="btn-submit" value="ENTRAR">
                <script>
                    btnLogin.addEventListener('click', async () => {
                        const emailInvalid = !email.value.length;
                        const passwordInvalid = !password.value.length;

                        (emailInvalid) ? email.classList.add('error'): email.classList.remove('error');
                        (passwordInvalid) ? password.classList.add('error'): password.classList.remove('error');

                        const response = await login.attempt(email.value, password.value);

                        if (response)
                            if (response.redirect !== '')
                                window.location.href = response.redirect;
                            else {
                                const span = document.createElement('span');
                                span.textContent = response.message;
                                span.style.color = "red";
                                setTimeout(() => span.remove(), 5000);
                                document.querySelector('.box-login .inputs').appendChild(span);
                            }
                    });
                </script>
            </div>
        </div>
    </div>
</div>