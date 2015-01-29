<?php
/*
	Score, a MediaWiki extension for rendering musical scores with LilyPond.
	Copyright © 2011 Alexander Klauer

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.

	To contact the author:
	<Graf.Zahl@gmx.net>
	http://en.wikisource.org/wiki/User_talk:GrafZahl
	https://github.com/TheCount/score

 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "This file cannot be run standalone.\n" );
}

/**
 * Score extension
 *
 * @file
 * @ingroup Extensions
 *
 * @author Alexander Klauer <Graf.Zahl@gmx.net>
 * @license GPL v3 or later
 * @version 0.2
 */

/*
 * Configuration
 */

/* Whether to trim the score images. Requires ImageMagick.
 *  Default is $wgUseImageMagick and set in efScoreExtension */
$wgScoreTrim = null;

/* Path to LilyPond executable */
$wgScoreLilyPond = '/usr/bin/lilypond';

/* Path to converter from ABC */
$wgScoreAbc2Ly = '/usr/bin/abc2ly';

/* Path to TiMidity++ */
$wgScoreTimidity = '/usr/bin/timidity';

/**
 * The URL path of the directory where files will be stored.
 * Defaults to "{$wgUploadPath}/lilypond".
 */
$wgScorePath = false;

/**
 * The filesystem path where files will be stored.
 * Defaults to "{$wgUploadDirectory}/lilypond".
 */
$wgScoreDirectory = false;

/**
 * The name of a file backend ($wgFileBackends) to be used for storing files.
 * Defaults to FSFileBackend using $wgScoreDirectory as a base path. Files will
 * be stored in a container internally called "score-render".
 */
$wgScoreFileBackend = false;

/*
 * Extension credits
 */
$wgExtensionCredits['parserhooks'][] = array(
	'name' => 'Score',
	'path' => __FILE__,
	'version' => '0.3.0',
	'author' => 'Alexander Klauer',
	'url' => 'https://www.mediawiki.org/wiki/Extension:Score',
	'descriptionmsg' => 'score-desc',
	'license-name' => 'GPL-3.0+'
);

/*
 * Setup
 */
$scoreBase = __DIR__;
$wgHooks['ParserFirstCallInit'][] = 'efScoreExtension';
$wgMessagesDirs['Score'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['Score'] = "$scoreBase/Score.i18n.php";
$wgAutoloadClasses['Score'] = "$scoreBase/Score.body.php";
$wgAutoloadClasses['ScoreException'] = "$scoreBase/Score.body.php";
$wgTrackingCategories[] = 'score-error-category';

/**
 * Init routine.
 *
 * @param $parser Parser Mediawiki parser
 *
 * @return bool Returns true
 */
function efScoreExtension( Parser &$parser ) {
	global $wgUseImageMagick, $wgScoreTrim;

	if ( $wgScoreTrim === null ) {
		// Default to if we use Image Magick, since it requires Image Magick.
		$wgScoreTrim = $wgUseImageMagick;
	}

	$parser->setHook( 'score', 'Score::render' );
	return true;
}
