<?php

namespace Np\core;

/**
 * php interface for LAPACK
 * 
 * @package NumPhp
 * @category Scientific Computing
 * @author ghost (Shubham Chaudhary)
 * @email ghost.jat@gmail.com
 * @copyright (c) 2020-2021, Shubham Chaudhary
 */
class lapack {

    const ROW_MAJOR = 101, COL_MAJOR = 102;
    const Upper = 'U', Lower = 'L';
    const INCR = 'I', DECR = 'D';

    public static $ffi_lapack;

    public static function init() {
        if (is_null(self::$ffi_lapack)) {
            self::$ffi_lapack = \FFI::load(__DIR__ . '/lapack.h');
        }
        return self::$ffi_lapack;
    }

    /**
     * 
     * @param \Np\matrix $mat
     * @param \Np\vector $ipiv
     * @param int $matLayout
     * @return int
     */
    public static function getrf(\Np\matrix $mat, \Np\vector $ipiv, int $matLayout = self::ROW_MAJOR) {
        self::init();
        if ($mat->dtype == \Np\matrix::FLOAT) {
            return self::$ffi_lapack->LAPACKE_sgetrf($matLayout, $mat->row, $mat->col, $mat->data, $mat->row, $ipiv->data);
        } else {
            return self::$ffi_lapack->LAPACKE_dgetrf($matLayout, $mat->row, $mat->col, $mat->data, $mat->row, $ipiv->data);
        }
    }

    /**
     * 
     * @param \Np\matrix $mat
     * @param \Np\vector $ipiv
     * @param int $matLayout
     * @return int
     */
    public static function getri(\Np\matrix $mat, \Np\vector $ipiv, int $matLayout = self::ROW_MAJOR) {
        self::init();
        if($mat->dtype == \Np\matrix::FLOAT){
            return self::$ffi_lapack->LAPACKE_sgetri($matLayout, $mat->row, $mat->data, $mat->row, $ipiv->data);
        }
        else {
            return self::$ffi_lapack->LAPACKE_dgetri($matLayout, $mat->row, $mat->data, $mat->row, $ipiv->data);
        }
    }

    /**
     * 
     * @param \Np\matrix $mat
     * @param \Np\vector $s
     * @param \Np\matrix $u
     * @param \Np\matrix $vt
     * @param int $matLayout
     * @return int
     */
    public static function gesdd(\Np\matrix $mat, \Np\vector $s, \Np\matrix $u, \Np\matrix $vt, int $matLayout = self::ROW_MAJOR) {
        self::init();
        if ($mat->dtype == \Np\matrix::FLOAT) {
            return self::$ffi_lapack->LAPACKE_sgesdd($matLayout, 'A', $mat->row, $mat->col, $mat->data, $mat->col, $s->data, $u->data, $mat->row, $vt->data, $mat->col);
        } else {
            return self::$ffi_lapack->LAPACKE_dgesdd($matLayout, 'A', $mat->row, $mat->col, $mat->data, $mat->col, $s->data, $u->data, $mat->row, $vt->data, $mat->col);
        }
    }

    /**
     * 
     * @param \Np\matrix $mat
     * @param string $uplo
     * @param int $matLayout
     * @return int
     */
    public static function potrf(\Np\matrix $mat, $uplo = self::Lower, int $matLayout = self::ROW_MAJOR) {
        self::init();
        if ($mat->dtype == \Np\matrix::FLOAT) {
            return self::$ffi_lapack->LAPACKE_spotrf($matLayout, $uplo, $mat->col, $mat->data, $mat->col);
        } else {
            return self::$ffi_lapack->LAPACKE_dpotrf($matLayout, $uplo, $mat->col, $mat->data, $mat->col);
        }
    }

    /**
     * 
     * @param \Np\matrix $mat
     * @param \Np\vector $wr
     * @param \Np\vector $wi
     * @param \Np\matrix $vr
     * @param int $matLayout
     * @return int
     */
    public static function geev(\Np\matrix $mat, \Np\vector $wr, \Np\vector $wi, \Np\matrix $vr, int $matLayout = self::ROW_MAJOR) {
        self::init();
        if ($mat->dtype == \Np\matrix::FLOAT) {
            return self::$ffi_lapack->LAPACKE_sgeev($matLayout, 'N', 'V', $mat->col, $mat->data, $mat->col, $wr->data, $wi->data, null, $mat->col, $vr->data, $mat->col);
        } else {
            return self::$ffi_lapack->LAPACKE_dgeev($matLayout, 'N', 'V', $mat->col, $mat->data, $mat->col, $wr->data, $wi->data, null, $mat->col, $vr->data, $mat->col);
        }
    }

    /**
     * 
     * @param \Np\matrix $mat
     * @param \Np\vector $wr
     * @param int $matLayout
     * @return int
     */
    public static function syev(\Np\matrix $mat, \Np\vector $wr, int $matLayout = self::ROW_MAJOR) {
        self::init();
        if ($mat->dtype == \Np\matrix::FLOAT) {
            return self::$ffi_lapack->LAPACKE_ssyev($matLayout, 'V', 'U', $mat->col, $mat->data, $mat->col, $wr->data);
        } else {
            return self::$ffi_lapack->LAPACKE_dsyev($matLayout, 'V', 'U', $mat->col, $mat->data, $mat->col, $wr->data);
        }
    }

    /**
     * 
     * @param string $norm
     * @param \Np\matrix $m
     * @param int $matLayout
     * @return \FFI\CData
     */
    public static function lange(string $norm, \Np\matrix $m, int $matLayout = self::ROW_MAJOR) {
        self::init();
        if($m->dtype == \Np\matrix::FLOAT){
            return self::$ffi_lapack->LAPACKE_slange($matLayout, $norm, $m->row, $m->col, $m->data, $m->col);
        }
        else{
            return self::$ffi_lapack->LAPACKE_dlange($matLayout, $norm, $m->row, $m->col, $m->data, $m->col);
        }   
    }

    /**
     * 
     * @param \Np\vector $v
     * @param  $id
     * @return \FFI\CData
     */
    public static function sort(\Np\vector $v, $id = self::INCR) {
        self::init();
        if ($v->dtype == \Np\vector::FLOAT) {
            return self::$ffi_lapack->LAPACKE_slasrt($id, $v->col, $v->data);
        } else {
            return self::$ffi_lapack->LAPACKE_dlasrt($id, $v->col, $v->data);
        }
    }

    public static function sgels() {
        self::init();
    }

}