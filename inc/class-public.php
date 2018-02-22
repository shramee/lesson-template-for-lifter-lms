<?php

/**
 * Lesson template for lifter LMS public class
 */
class Lesson_Template_LLMS_Public{

	/** @var Lesson_Template_LLMS_Public Instance */
	private static $_instance = null;

	/* @var string $token Plugin token */
	public $token;

	/* @var string $url Plugin root dir url */
	public $url;

	/* @var string $path Plugin root dir path */
	public $path;

	/* @var string $version Plugin version */
	public $version;

	/** @var LLMS_Lesson Instance */
	private $l;

	/**
	 * Constructor function.
	 * @access  private
	 * @since   1.0.0
	 */
	private function __construct() {
		$this->token   =   Lesson_Template_LLMS::$token;
		$this->url     =   Lesson_Template_LLMS::$url;
		$this->path    =   Lesson_Template_LLMS::$path;
		$this->version =   Lesson_Template_LLMS::$version;
	}

	/**
	 * Lesson template for lifter LMS public class instance
	 * @return Lesson_Template_LLMS_Public instance
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 */
	public function init() {
		$token = $this->token;
		$url = $this->url;

		if ( is_singular( 'lesson' ) ) {
			remove_action( 'lifterlms_single_lesson_before_summary', 'lifterlms_template_single_parent_course', 10 );
			remove_action( 'lifterlms_single_lesson_before_summary', 'lifterlms_template_single_lesson_video',  20 );
			remove_action( 'lifterlms_single_lesson_before_summary', 'lifterlms_template_single_lesson_audio',  20 );

			add_action( 'lifterlms_single_lesson_before_summary', [ $this, 'lesson_markup' ], 10 );
			add_action( 'lifterlms_single_lesson_before_summary', [ $this, 'lesson_title' ],  20 );
			add_action( 'lifterlms_single_lesson_before_summary', [ $this, 'lesson_video' ],  30 );

			remove_action( 'lifterlms_single_lesson_after_summary', 'lifterlms_template_complete_lesson_link',  10 );
			remove_action( 'lifterlms_single_lesson_after_summary', 'lifterlms_template_lesson_navigation',     20 );

			add_action( 'lifterlms_single_lesson_after_summary', [ $this, 'lesson_actions' ], 10 );
			add_action( 'lifterlms_single_lesson_after_summary', [ $this, 'markup_close_div' ], 20 ); // #lesson-primary
			add_action( 'lifterlms_single_lesson_after_summary', [ $this, 'lesson_markup_sidebar' ], 30 );
			add_action( 'lifterlms_single_lesson_after_summary', [ $this, 'course_index' ],  40 );
			add_action( 'lifterlms_single_lesson_after_summary', [ $this, 'course_progress' ],  50 );
			add_action( 'lifterlms_single_lesson_after_summary', [ $this, 'markup_close_div' ], 70 ); // #right-sidebar
			add_action( 'lifterlms_single_lesson_after_summary', [ $this, 'markup_close_div' ], 70 ); // #lesson

			wp_enqueue_style( $token . '-css', $url . '/assets/front.css' );
			wp_enqueue_script( $token . '-js', $url . '/assets/front.js', array( 'jquery' ) );
		}
	}

	/**
	 * Outputs lesson title
	 */
	public function lesson_markup() {
		?>
		<div id="lesson">
		<div id="lesson-primary" class="content-area grid-parent mobile-grid-100 grid-65 tablet-grid-65">
		<?php
	}

	/**
	 * Outputs lesson title
	 */
	public function markup_close_div() {
		?>
		</div>
		<?php
	}

	/**
	 * Outputs lesson title
	 */
	public function lesson_markup_sidebar() {
		?>
		<div id="right-sidebar" itemtype="http://schema.org/WPSideBar" itemscope="itemscope" role="complementary" class="widget-area grid-35 tablet-grid-35 grid-parent sidebar">
		<?php
	}

	/**
	 * Outputs lesson title
	 */
	public function lesson_title() {
		the_title( '<h2 class="lesson-title">', '</h2>' );
	}

	/**
	 * Outputs lesson video
	 */
	public function lesson_video() {
		lifterlms_template_single_lesson_video();
	}

	/**
	 * Outputs lesson actions
	 */
	public function lesson_actions() {
		global $post;

		$this->l = new LLMS_Lesson( $post->ID );

		$prev_id = $this->l->get_previous_lesson();
		$next_id = $this->l->get_next_lesson();

		?>
		<div id="wixbu-lesson-actions">
			<?php lifterlms_template_complete_lesson_link() ?>
			<div id="wixbu-lesson-navigation">
				<?php
				if ( $prev_id ) {
					echo '<a class="button" href="' . get_the_permalink( $prev_id ) . '">' . __( 'Previous Lesson', 'lifterlms' ) . '</a>';
				}
				if ( $next_id ) {
					echo '<a class="button" href="' . get_the_permalink( $next_id ) . '">' . __( 'Next Lesson', 'lifterlms' ) . '</a>';
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs course info
	 */
	public function course_index() {
			$section = new LLMS_Section( $this->l->get_parent_section() );
			$course = $this->l->get_course();
		?>
		<div class="index">
			<div class="index-tabs">
				<a href="#index-section" data-title="<?php echo $section->get( 'title' ) ?>" class="button active">Section lessons</a>
				<a href="#index-course" data-title="<?php echo $course->get( 'title' ) ?>" class="button">Course program</a>
			</div>
			<div class="index-tabs-content">
				<div id="index-course" class="index-tab-content">
					<?php echo do_shortcode( '[lifterlms_course_outline]' ); ?>
				</div>
				<div id="index-section" class="index-tab-content">
					<?php $this->section_outline( $section ); ?>
				</div>
			<div id="index-tab-title" class="button bottom-fix"><?php echo $section->get( 'title' ) ?></div>
			</div>
		</div>
		<?php
	}

	/**
	 * @param LLMS_Section $section
	 */
	public function section_outline( $section ) {
		?>
		<div id="llms-section-lessons" class="llms-widget-syllabus">
			<?php
			$student = llms_get_student();
			$lessons = $section->get_lessons();

			foreach ( $lessons as $lesson ) {
				/** @var LLMS_Lesson $lesson */
				$done = $student->is_complete( $lesson->get( 'id' ), 'lesson' ) ? 'done' : '';
				llms_get_template( 'course/lesson-preview.php', array(
					'pre_text' => "<span class='$done llms-lesson-complete'><i class='fa fa-check-circle-o'></i></span>",
					'lesson' => $lesson,
				) );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Outputs course progress
	 */
	public function course_progress() {
		$student = llms_get_student();
		$progress = $student->get_progress( $this->l->get_parent_course() );
		$progress = round( $progress );
		?>
		<div id="llms-course-progress" class="button llms-progress">
			<span class="progress-label"><?php _e( 'Progress', 'llms' ) ?></span>
			<span class="progress-bar">
				<span class="progress-bar-complete <?php echo $progress > 95 ? 'almost-done' : '' ?>" style="width:<?php echo $progress ?>%;"></span>
			</span>
			<span class="progress-percentage"><?php echo $progress ?>%</span>
		</div>
		<?php
	}

}