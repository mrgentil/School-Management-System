<?php

namespace App\Helpers;

class Toast
{
    /**
     * Afficher un toast de succès
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function success($message)
    {
        return back()->with('flash_success', $message);
    }

    /**
     * Afficher un toast d'erreur
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function error($message)
    {
        return back()->with('flash_error', $message);
    }

    /**
     * Afficher un toast d'avertissement
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function warning($message)
    {
        return back()->with('flash_warning', $message);
    }

    /**
     * Afficher un toast d'information
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function info($message)
    {
        return back()->with('flash_info', $message);
    }

    /**
     * Afficher un toast de danger (alias pour error)
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function danger($message)
    {
        return back()->with('flash_danger', $message);
    }

    /**
     * Afficher un pop-up de succès (SweetAlert)
     *
     * @param string $message
     * @param string $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function popSuccess($message, $title = 'Succès!')
    {
        return back()->with('pop_success', $message);
    }

    /**
     * Afficher un pop-up d'erreur (SweetAlert)
     *
     * @param string $message
     * @param string $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function popError($message, $title = 'Erreur!')
    {
        return back()->with('pop_error', $message);
    }

    /**
     * Afficher un pop-up d'avertissement (SweetAlert)
     *
     * @param string $message
     * @param string $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function popWarning($message, $title = 'Attention!')
    {
        return back()->with('pop_warning', $message);
    }

    /**
     * Méthode générique pour afficher un toast
     *
     * @param string $type (success, error, warning, info, danger)
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function show($type, $message)
    {
        return back()->with('flash_' . $type, $message);
    }

    /**
     * Rediriger vers une route avec un toast de succès
     *
     * @param string $route
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function redirectSuccess($route, $message)
    {
        return redirect()->route($route)->with('flash_success', $message);
    }

    /**
     * Rediriger vers une route avec un toast d'erreur
     *
     * @param string $route
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function redirectError($route, $message)
    {
        return redirect()->route($route)->with('flash_error', $message);
    }
}
