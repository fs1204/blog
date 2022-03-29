'use strict';

{
    const deletes = document.querySelectorAll('.delete');
    deletes.forEach(span => {
        span.addEventListener('click', e => {
            e.preventDefault();

            if (!confirm('削除しますか?')) {
                return;
            }

            span.parentNode.submit();
        });
    });

    const logout = document.querySelector('#logout');
    logout.addEventListener('click', e => {
        e.preventDefault();

        if (!confirm('ログアウトしますか?')) {
            return;
        }

        logout.parentNode.submit();
    });

    const user_delete = document.querySelector('#delete-user');
    user_delete.addEventListener('click', e => {
        e.preventDefault();

        if (!confirm('アカウントを削除しますか?')) {
            return;
        }

        user_delete.parentNode.submit();
    });
}
