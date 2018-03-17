<?php

/**
 * Template for output of a single wishlist
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<section class="wpgr-wishlist" data-id="<?= $atts['id'] ?>">

<?php
    $i = 0;

    foreach ( $wishlist as $gift ):

        $is_available = $gift['gift_availability'] == 'true';
        $is_single = true; // needs to be replaced
        $has_buyer = !empty($gift['gift_reserver']);
        $gift_price = number_format_i18n( $gift['gift_price'] );

        $classes = array('wpgr-m_card');

        if ( !$is_available ) {
            $classes[] = 'wpgr-m_card--bought';
        }
        if ( $is_single ) {
            $classes[] = 'wpgr-m_card--single';
        }

    ?>

        <div class="<?= implode(' ', $classes) ?>" data-wish-id="<?= $gift['gift_id'] ?>">

        <?php if ( !empty($gift_price) ): ?>
            <div class="wpgr-m_card__price-wrapper">
                <p class="wpgr-m_card__price">
                    <?= $currency_placement === 'before' ? $currency . $gift_price : $gift_price . $currency ?>
                </p>
                <?php if ( !$is_single ): ?>
                    <p class="wpgr-m_card__price-text">
                    <?php
                        /* translators: (price per) each part of the gift */
                        echo __('each', 'wpgiftregistry');
                    ?>
                    </p>
                <?php endif; ?>
                <i class="wpgr-m_card__price-icon"></i>
            </div>
        <?php endif; ?>

            <div class="wpgr-m_card__main">
                <div class="wpgr-m_card__figure-wrapper">
                    <div class="wpgr-m_card__figure" <?= empty($gift['gift_image']) ? '' : "style='background-image:url(" . $gift['gift_image'] . ")'" ?>></div>
                </div>
                <div class="wpgr-m_card__content">
                    <?php if ( !empty($gift['gift_title']) ): ?>
                        <h4 class="wpgr-m_card__heading"><?= $gift['gift_title'] ?></h4>
                    <?php endif; ?>
                    <div class="wpgr-m_card__content-details">
                        <?php if ( !empty($gift['gift_description']) ): ?>
                            <p class="wpgr-m_card__desc"><?= $gift['gift_description'] ?></p>
                        <?php endif; ?>
                        <div class="wpgr-m_card__btn-wrapper">
                            <?php if ( !empty($gift['gift_url']) ): ?>
                                <a class="wpgr-m_card__btn wpgr-m_btn" target="_blank" href="<?= transform_to_affiliate_link( $gift['gift_url'] ) ?>">
                                    <span class="wpgr-m_card__btn-text"><?= __('View', 'wpgiftregistry') ?></span>
                                    <i class="wpgr-m_card__btn-icon wpgr-m_card__btn-icon--view"></i>
                                </a>
                            <?php endif; ?>
                            <button class="wpgr-m_card__btn wpgr-m_btn wpgr-m_btn__open" type="button" name="button">
                                <span class="wpgr-m_card__btn-text"><?= $is_single ? __('Give', 'wpgiftregistry') : __('Give Part', 'wpgiftregistry') ?></span>
                                <i class="wpgr-m_card__btn-icon wpgr-m_card__btn-icon--give"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ( !$is_single ): ?>
                <footer class="wpgr-m_card__footer is-hidden">
                    <div class="wpgr-m_card__buyer-wrapper">
                        <i class="wpgr-m_card__buyer"></i>
                    </div>
                    <div class="wpgr-m_card__process">
                        <div class="wpgr-m_card__process-bar"></div>
                        <p class="wpgr-m_card__process-price">
                            <span class="wpgr-m_card__process-price-partial">100€</span>
                            <span class="wpgr-m_card__process-price-total"> / 200€</span>
                        </p>
                        <p class="wpgr-m_card__process-count">
                            <span class="wpgr-m_card__process-count-partial">10</span>
                            <span class="wpgr-m_card__process-count-total"> / 20</span>
                        </p>
                    </div>
                </footer>
            <?php endif; ?>
            <!-- <div class="wpgr-m_card__toggle">
                <i class="wpgr-m_card__toggle-icon"></i>
            </div> -->
        </div>
<?php
    endforeach;
?>

    <!-- <div class="wpgr-m_card">
        <div class="wpgr-m_card__price-wrapper">
            <p class="wpgr-m_card__price">20€</p>
            <p class="wpgr-m_card__price-text">each</p>
        </div>
        <div class="wpgr-m_card__main">
            <div class="wpgr-m_card__figure-wrapper">
                <div class="wpgr-m_card__figure"></div>
            </div>
            <div class="wpgr-m_card__content">
                <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                <div class="wpgr-m_card__content-details is-hidden">
                    <p class="wpgr-m_card__desc">
                        Professionally enable revolutionary ideas vis-a-vis premium human capital.
                        Progressively strategize client-focused processes via resource-leveling growth strategies.
                    </p>
                    <div class="wpgr-m_card__btn-wrapper">
                        <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                        <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                    </div>
                </div>
            </div>
        </div>
        <footer class="wpgr-m_card__footer is-hidden">
            <div class="wpgr-m_card__buyer-wrapper">
                <i class="wpgr-m_card__buyer"></i>
            </div>
            <div class="wpgr-m_card__process">
                <div class="wpgr-m_card__process-bar"></div>
                <p class="wpgr-m_card__process-price">
                    <span class="wpgr-m_card__process-price-partial">100€</span>
                    <span class="wpgr-m_card__process-price-total"> / 200€</span>
                </p>
                <p class="wpgr-m_card__process-count">
                    <span class="wpgr-m_card__process-count-partial">10</span>
                    <span class="wpgr-m_card__process-count-total"> / 20</span>
                </p>
            </div>
        </footer>
        <div class="wpgr-m_card__toggle">
            <i class="wpgr-m_card__toggle-icon"></i>
        </div>
    </div>

    <div class="wpgr-m_card is-collapsed">
        <div class="wpgr-m_card__price-wrapper">
            <p class="wpgr-m_card__price">20€</p>
            <p class="wpgr-m_card__price-text">each</p>
        </div>
        <div class="wpgr-m_card__main">
            <div class="wpgr-m_card__figure-wrapper">
                <div class="wpgr-m_card__figure"></div>
            </div>
            <div class="wpgr-m_card__content">
                <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                <div class="wpgr-m_card__content-details is-hidden">
                    <p class="wpgr-m_card__desc">
                        Professionally enable revolutionary ideas vis-a-vis premium human capital.
                        Progressively strategize client-focused processes via resource-leveling growth strategies.
                    </p>
                    <div class="wpgr-m_card__btn-wrapper">
                        <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                        <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                    </div>
                </div>
            </div>
        </div>
        <footer class="wpgr-m_card__footer is-hidden">
            <div class="wpgr-m_card__buyer-wrapper">
                <i class="wpgr-m_card__buyer"></i>
            </div>
            <div class="wpgr-m_card__process">
                <div class="wpgr-m_card__process-bar"></div>
                <p class="wpgr-m_card__process-price">
                    <span class="wpgr-m_card__process-price-partial">100€</span>
                    <span class="wpgr-m_card__process-price-total"> / 200€</span>
                </p>
                <p class="wpgr-m_card__process-count">
                    <span class="wpgr-m_card__process-count-partial">10</span>
                    <span class="wpgr-m_card__process-count-total"> / 20</span>
                </p>
            </div>
        </footer>
        <div class="wpgr-m_card__toggle">
            <i class="wpgr-m_card__toggle-icon"></i>
        </div>
    </div>

    <div class="wpgr-m_card wpgr-m_card--single">
        <div class="wpgr-m_card__price-wrapper">
            <p class="wpgr-m_card__price">20€</p>
            <p class="wpgr-m_card__price-text">each</p>
        </div>
        <div class="wpgr-m_card__main">
            <div class="wpgr-m_card__figure-wrapper">
                <div class="wpgr-m_card__figure"></div>
            </div>
            <div class="wpgr-m_card__content">
                <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                <div class="wpgr-m_card__content-details is-hidden">
                    <p class="wpgr-m_card__desc">
                        Professionally enable revolutionary ideas vis-a-vis premium human capital.
                        Progressively strategize client-focused processes via resource-leveling growth strategies.
                    </p>
                    <div class="wpgr-m_card__btn-wrapper">
                        <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                        <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpgr-m_card__toggle">
            <i class="wpgr-m_card__toggle-icon"></i>
        </div>
    </div>

    <div class="wpgr-m_card wpgr-m_card--single is-collapsed">
        <div class="wpgr-m_card__price-wrapper">
            <p class="wpgr-m_card__price">20€</p>
            <p class="wpgr-m_card__price-text">each</p>
        </div>
        <div class="wpgr-m_card__main">
            <div class="wpgr-m_card__figure-wrapper">
                <div class="wpgr-m_card__figure"></div>
            </div>
            <div class="wpgr-m_card__content">
                <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                <div class="wpgr-m_card__content-details is-hidden">
                    <p class="wpgr-m_card__desc">
                        Professionally enable revolutionary ideas vis-a-vis premium human capital.
                        Progressively strategize client-focused processes via resource-leveling growth strategies.
                    </p>
                    <div class="wpgr-m_card__btn-wrapper">
                        <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                        <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpgr-m_card__toggle">
            <i class="wpgr-m_card__toggle-icon"></i>
        </div>
    </div>

    <div class="wpgr-m_card wpgr-m_card--single wpgr-m_card--bought is-collapsed">
        <div class="wpgr-m_card__price-wrapper">
            <i class="wpgr-m_card__price-icon"></i>
        </div>
        <div class="wpgr-m_card__main">
            <div class="wpgr-m_card__figure-wrapper">
                <div class="wpgr-m_card__figure"></div>
            </div>
            <div class="wpgr-m_card__content">
                <h4 class="wpgr-m_card__heading">Ein Papierschiffchen mit Anker!</h4>
                <div class="wpgr-m_card__content-details is-hidden">
                    <p class="wpgr-m_card__desc">
                        Professionally enable revolutionary ideas vis-a-vis premium human capital.
                        Progressively strategize client-focused processes via resource-leveling growth strategies.
                    </p>
                    <div class="wpgr-m_card__btn-wrapper">
                        <a class="wpgr-m_card__btn wpgr-m_btn" href="#">Anschauen</a>
                        <button class="wpgr-m_card__btn wpgr-m_btn" type="button" name="button">Mitschenken</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpgr-m_card__toggle">
            <i class="wpgr-m_card__toggle-icon"></i>
        </div>
    </div> -->

    <form class="wpgr-o_popup wpgr-o_popup--single wpgr-o_popup__form">

        <div id="wpgr_popup_name" class="wpgr-o_popup__step wpgr-o_popup__step--1">
            <header class="wpgr-o_popup__header">
                <p class="wpgr-o_popup__question">Geschenk reservieren?</p>
                <p class="wpgr-o_popup__desc">Lasse den Beschenkten wissen, dass das Geschenk von Dir ist.</p>
            </header>

            <div class="wpgr-o_popup__input-wrapper">
                <label class="wpgr-o_popup__input-label" for="your_name2">Dein Name</label>
                <input id="your_name2" class="wpgr-o_popup__input-text" type="text">
            </div>
            <div class="wpgr-o_popup__btn-wrapper">
                <button class="wpgr-o_popup__btn-prev wpgr-m_btn">Abbrechen</button>
                <input class="wpgr-o_popup__btn-save wpgr-m_btn wpgr-m_btn--next" type="submit" name="confirm" value="Bestätigen">
            </div>

            <button class="wpgr-o_popup__btn-close wpgr-m_btn-close">
                <i class="wpgr-m_btn-close-icon"></i>
            </button>
        </div>

        <div id="wpgr_popup_parts" class="wpgr-o_popup__step wpgr-o_popup__step--1">
            <header class="wpgr-o_popup__header">
                <p class="wpgr-o_popup__question">Wieviele Teile möchtest Du schenken?</p>
                <p class="wpgr-o_popup__desc">Ein Teil entspricht 50€.</p>
            </header>

            <div class="wpgr-o_popup__input-wrapper">
                <label class="wpgr-o_popup__input-label" for="part_number">Anteil</label>
                <input id="part_number" class="wpgr-o_popup__input-number" type="number">
            </div>

            <div class="wpgr-o_popup__btn-wrapper">
                <button class="wpgr-o_popup__btn-next wpgr-m_btn wpgr-m_btn--next">Weiter</button>
            </div>
            <button class="wpgr-o_popup__btn-close wpgr-m_btn-close">
                <i class="wpgr-m_btn-close-icon"></i>
            </button>
            <ul class="wpgr-o_popup__process">
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-01"></li>
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-02"></li>
            </ul>
        </div>

        <div id="wpgr_popup_buyer" class="wpgr-o_popup__step wpgr-o_popup__step--2">
            <header class="wpgr-o_popup__header">
                <p class="wpgr-o_popup__question">Geschenk reservieren?</p>
                <p class="wpgr-o_popup__desc">Lasse den Beschenkten wissen, dass das Geschenk von Dir ist.</p>
            </header>

            <div class="wpgr-o_popup__input-wrapper">
                <label class="wpgr-o_popup__input-label" for="your_name">Dein Name</label>
                <input id="your_name" class="wpgr-o_popup__input-text" type="text">
            </div>

            <div class="wpgr-o_popup__buyer">
                <p class="wpgr-o_popup__question">Willst du den Kauf übernehmen?</p>
                <p class="wpgr-o_popup__desc">Übernheme die Organisation und hinterlege Deine Kontaktdaten.</p>
                <div class="wpgr-o_popup__radio-wrapper">
                    <input class="wpgr-o_popup__radio-btn" type="radio" id="buyer_yes" name="buyer" value="yes">
                    <label class="wpgr-o_popup__radio-label" for="buyer_yes">Ja</label>
                </div>
                <div class="wpgr-o_popup__radio-wrapper">
                    <input class="wpgr-o_popup__radio-btn" type="radio" id="buyer_no" name="buyer" value="no" checked>
                    <label class="wpgr-o_popup__radio-label" for="buyer_no">Nein</label>
                </div>
            </div>

            <div class="wpgr-m_btn__buyer-content">
                <label class="wpgr-o_popup__input-label" for="contact">Deine Kontaktdaten</label>
                <textarea id="contact" class="wpgr-o_popup__input-textarea"></textarea>
            </div>

            <div class="wpgr-o_popup__btn-wrapper">
                <button class="wpgr-o_popup__btn-prev wpgr-m_btn wpgr-m_btn--prev">Abbrechen</button>
                <input class="wpgr-o_popup__btn-save wpgr-m_btn wpgr-m_btn--next" type="submit" name="confirm" value="Bestätigen">
            </div>

            <button class="wpgr-o_popup__btn-close wpgr-m_btn-close">
                <i class="wpgr-m_btn-close-icon"></i>
            </button>

            <ul class="wpgr-o_popup__process">
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-01 is-active"></li>
                <li class="wpgr-o_popup__list-item wpgr-o_popup__list-item-02"></li>
            </ul>
        </div>
    </form>

</section>
