<div class="container mt-3">

    <button class="button is-primary is-dark is-small js-modal-trigger" data-target="modal-js-example">Añadir</button>
    <button class="button is-primary is-dark is-small">Ver</button>
    <button class="button is-primary is-dark is-small">Eliminar</button>
    
    <table class="table is-hoverable is-fullwidth is-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Luis Santiago</td>
                <td>Noh Cahum</td>
            </tr>
        </tbody>
    </table>


    <!-- Modal with AJAX -->
    <div id="modal-js-example" class="modal">
        <div class="modal-background"></div>

        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Modal title</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <form action="" method="post">
                    <div class="field">
                        <label class="label">Nombre</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="Name">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Apellido</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="Last Name">
                        </div>
                    </div>
                </form>
            </section>
            <footer class="modal-card-foot">
                <div class="buttons">
                    <button class="button is-success">Save changes</button>
                    <button class="button">Cancel</button>
                </div>
            </footer>
        </div>

        <button class="modal-close is-large" aria-label="close"></button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Functions to open and close a modal
        function openModal($el) {
            $el.classList.add('is-active');
        }

        function closeModal($el) {
            $el.classList.remove('is-active');
        }

        function closeAllModals() {
            (document.querySelectorAll('.modal') || []).forEach(($modal) => {
                closeModal($modal);
            });
        }

        // Add a click event on buttons to open a specific modal
        (document.querySelectorAll('.js-modal-trigger') || []).forEach(($trigger) => {
            const modal = $trigger.dataset.target;
            const $target = document.getElementById(modal);

            $trigger.addEventListener('click', () => {
                openModal($target);
            });
        });

        // Add a click event on various child elements to close the parent modal
        (document.querySelectorAll('.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button') || []).forEach(($close) => {
            const $target = $close.closest('.modal');

            $close.addEventListener('click', () => {
                closeModal($target);
            });
        });

        // Add a keyboard event to close all modals
        document.addEventListener('keydown', (event) => {
            if (event.key === "Escape") {
                closeAllModals();
            }
        });
    });
</script>