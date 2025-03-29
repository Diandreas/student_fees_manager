<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Sauvegarde une image d'étudiant
     *
     * @param UploadedFile $file
     * @return string|null
     */
    public function saveStudentPhoto(UploadedFile $file)
    {
        try {
            Log::info('Début du stockage de la photo');
            
            // Générer un nom de fichier unique
            $fileName = 'student_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            Log::info('Nom du fichier généré : ' . $fileName);
            
            // Vérifier le type MIME
            Log::info('Type MIME du fichier : ' . $file->getMimeType());
            
            // Sauvegarder le fichier en utilisant le disque public
            Log::info('Tentative de stockage du fichier');
            $path = $file->storeAs('students', $fileName, 'public');
            
            if (!$path) {
                Log::error('Échec du stockage du fichier');
                throw new \Exception('Erreur lors du stockage de la photo');
            }
            
            Log::info('Fichier stocké avec succès : ' . $path);
            
            // Vérifier que le fichier existe
            if (!Storage::disk('public')->exists($path)) {
                Log::error('Le fichier n\'existe pas après le stockage');
                throw new \Exception('Le fichier n\'a pas été créé');
            }
            
            // Vérifier les permissions
            $filePath = storage_path('app/public/' . $path);
            Log::info('Chemin complet du fichier : ' . $filePath);
            
            if (!file_exists($filePath)) {
                Log::error('Le fichier n\'existe pas au chemin : ' . $filePath);
                throw new \Exception('Le fichier n\'existe pas au chemin spécifié');
            }
            
            // Définir les permissions du fichier
            chmod($filePath, 0644);
            
            if (!is_readable($filePath)) {
                Log::error('Le fichier n\'est pas lisible : ' . $filePath);
                throw new \Exception('Le fichier n\'est pas lisible');
            }
            
            Log::info('Photo stockée avec succès');
            return $fileName;
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du stockage de la photo : ' . $e->getMessage());
            Log::error('Trace : ' . $e->getTraceAsString());
            return null;
        }
    }
    
    /**
     * Supprime une image d'étudiant
     *
     * @param string $fileName
     * @return bool
     */
    public function deleteStudentPhoto($fileName)
    {
        try {
            if ($fileName && Storage::disk('public')->exists('students/' . $fileName)) {
                return Storage::disk('public')->delete('students/' . $fileName);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la photo : ' . $e->getMessage());
            return false;
        }
    }
} 