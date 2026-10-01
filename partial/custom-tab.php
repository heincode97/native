<?php

$fields = get_fields(get_the_ID());

$projects = $fields['products_tab'] ?? [];

?>


<section>
    <div class="container">
        <div class="row align-items-start g-4">

            <!-- Left Tabs -->
            <div class="col-lg-5 p-0">

                <div
                    class="project-tabs nav flex-column"
                    id="projectTabs"
                    role="tablist">

                    <?php foreach ($projects as $index => $project) :

                        $tab_id = 'project-' . $index;
                        $active = $index === 0;

                        $icon     = $project['icon'] ?? '';
                        $category = $project['category'] ?? '';
                        $period   = $project['period'] ?? '';
                        $title    = $project['title'] ?? '';

                        $icon_url = '';
                        $icon_alt = $title;

                        if (!empty($icon)) {

                            if (is_array($icon)) {

                                $icon_url = $icon['url'] ?? '';
                                $icon_alt = $icon['alt'] ?? $title;
                            } elseif (is_numeric($icon)) {

                                $icon_url = wp_get_attachment_image_url(
                                    $icon,
                                    'full'
                                );

                                $icon_alt = get_post_meta(
                                    $icon,
                                    '_wp_attachment_image_alt',
                                    true
                                ) ?: $title;
                            } else {

                                $icon_url = $icon;
                            }
                        }

                    ?>

                        <button
                            class="project-tab <?php echo $active ? 'active' : ''; ?>"
                            id="<?php echo esc_attr($tab_id); ?>-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#<?php echo esc_attr($tab_id); ?>"
                            type="button"
                            role="tab"
                            aria-controls="<?php echo esc_attr($tab_id); ?>"
                            aria-selected="<?php echo $active ? 'true' : 'false'; ?>">

                            <?php if ($icon_url) : ?>

                                <span class="project-icon">
                                    <img
                                        src="<?php echo esc_url($icon_url); ?>"
                                        alt="<?php echo esc_attr($icon_alt); ?>">
                                </span>

                            <?php endif; ?>


                            <span class="project-info">

                                <?php if ($category || $period) : ?>

                                    <small>

                                        <?php echo esc_html($category); ?>

                                        <?php if ($category && $period) : ?>
                                            <span> · </span>
                                        <?php endif; ?>

                                        <?php echo esc_html($period); ?>

                                    </small>

                                <?php endif; ?>


                                <?php if ($title) : ?>

                                    <strong>
                                        <?php echo esc_html($title); ?>
                                    </strong>

                                <?php endif; ?>

                            </span>

                        </button>

                    <?php endforeach; ?>

                </div>

                <a href="#" class="button button--medium button--primary mt-3">
                    <span>Explore Projects</span>
                </a>


            </div>


            <!-- Right Content -->
            <div class="col-lg-7">

                <div
                    class="tab-content project-content"
                    id="projectTabsContent">

                    <?php foreach ($projects as $index => $project) :

                        $tab_id = 'project-' . $index;
                        $active = $index === 0;

                        $category    = $project['category'] ?? '';
                        $title       = $project['title'] ?? '';
                        $description = $project['description'] ?? '';
                        $image       = $project['image'] ?? '';

                        $image_url = '';
                        $image_alt = $title;

                        if (!empty($image)) {

                            if (is_array($image)) {

                                $image_url = $image['url'] ?? '';
                                $image_alt = $image['alt'] ?? $title;
                            } elseif (is_numeric($image)) {

                                $image_url = wp_get_attachment_image_url(
                                    $image,
                                    'large'
                                );

                                $image_alt = get_post_meta(
                                    $image,
                                    '_wp_attachment_image_alt',
                                    true
                                ) ?: $title;
                            } else {

                                $image_url = $image;
                            }
                        }

                    ?>

                        <div
                            class="tab-pane fade <?php echo $active ? 'show active' : ''; ?>"
                            id="<?php echo esc_attr($tab_id); ?>"
                            role="tabpanel"
                            aria-labelledby="<?php echo esc_attr($tab_id); ?>-tab">

                            <div class="project-image">

                                <?php if ($image_url) : ?>

                                    <img
                                        src="<?php echo esc_url($image_url); ?>"
                                        alt="<?php echo esc_attr($image_alt); ?>">

                                <?php endif; ?>


                                <div class="project-overlay">

                                    <?php if ($category) : ?>

                                        <span class="category-label">
                                            <?php echo esc_html($category); ?>
                                        </span>

                                    <?php endif; ?>


                                    <?php if ($title) : ?>

                                        <h3>
                                            <?php echo esc_html($title); ?>
                                        </h3>

                                    <?php endif; ?>


                                    <?php if ($description) : ?>

                                        <p>
                                            <?php echo wp_kses_post($description); ?>
                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>
    </div>
</section>