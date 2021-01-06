<div class="column justify-center" style="background-color: #6f56f4">
    <div class="box-login">
        <h3>Faça seu cadastro ou entre se já tiver uma conta <a href="/login" style="color: red !important">clicando aqui</a></h3>
        <div class="inputs">
            <div class="input-group">
                <label id="labelName" for="name">Digite seu name</label>
                <input type="name" id="inputname" name="name" autocomplete="off">
                <script>
                    inputname.addEventListener('focus', () => labelName.classList.add('active'));
                    inputname.addEventListener('focusout', () => {
                        if (inputname.value.length == 0) labelName.classList.remove('active')
                    });
                </script>
            </div>
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
                <input type="submit" id="btnRegister" class="btn-submit" value="REGISTRAR">
                <script>
                    btnRegister.addEventListener('click', async () => {
                        const nameInvalid = !inputname.value.length;
                        const emailInvalid = !email.value.length;
                        const passwordInvalid = !password.value.length;

                        (nameInvalid) ? inputname.classList.add('error'): inputname.classList.remove('error');
                        (emailInvalid) ? email.classList.add('error'): email.classList.remove('error');
                        (passwordInvalid) ? password.classList.add('error'): password.classList.remove('error');

                        const response = await register.attempt(inputname.value, email.value, password.value);

                        if (response) {
                            window.location.href = response.redirect;
                        } else {
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