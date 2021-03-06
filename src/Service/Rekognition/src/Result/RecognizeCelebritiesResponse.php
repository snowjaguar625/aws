<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Rekognition\Enum\OrientationCorrection;
use AsyncAws\Rekognition\ValueObject\BoundingBox;
use AsyncAws\Rekognition\ValueObject\Celebrity;
use AsyncAws\Rekognition\ValueObject\ComparedFace;
use AsyncAws\Rekognition\ValueObject\Emotion;
use AsyncAws\Rekognition\ValueObject\ImageQuality;
use AsyncAws\Rekognition\ValueObject\KnownGender;
use AsyncAws\Rekognition\ValueObject\Landmark;
use AsyncAws\Rekognition\ValueObject\Pose;
use AsyncAws\Rekognition\ValueObject\Smile;

class RecognizeCelebritiesResponse extends Result
{
    /**
     * Details about each celebrity found in the image. Amazon Rekognition can detect a maximum of 64 celebrities in an
     * image. Each celebrity object includes the following attributes: `Face`, `Confidence`, `Emotions`, `Landmarks`,
     * `Pose`, `Quality`, `Smile`, `Id`, `KnownGender`, `MatchConfidence`, `Name`, `Urls`.
     */
    private $celebrityFaces;

    /**
     * Details about each unrecognized face in the image.
     */
    private $unrecognizedFaces;

    /**
     * > Support for estimating image orientation using the the OrientationCorrection field has ceased as of August 2021.
     * > Any returned values for this field included in an API response will always be NULL.
     */
    private $orientationCorrection;

    /**
     * @return Celebrity[]
     */
    public function getCelebrityFaces(): array
    {
        $this->initialize();

        return $this->celebrityFaces;
    }

    /**
     * @return OrientationCorrection::*|null
     */
    public function getOrientationCorrection(): ?string
    {
        $this->initialize();

        return $this->orientationCorrection;
    }

    /**
     * @return ComparedFace[]
     */
    public function getUnrecognizedFaces(): array
    {
        $this->initialize();

        return $this->unrecognizedFaces;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->celebrityFaces = empty($data['CelebrityFaces']) ? [] : $this->populateResultCelebrityList($data['CelebrityFaces']);
        $this->unrecognizedFaces = empty($data['UnrecognizedFaces']) ? [] : $this->populateResultComparedFaceList($data['UnrecognizedFaces']);
        $this->orientationCorrection = isset($data['OrientationCorrection']) ? (string) $data['OrientationCorrection'] : null;
    }

    /**
     * @return Celebrity[]
     */
    private function populateResultCelebrityList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Celebrity([
                'Urls' => !isset($item['Urls']) ? null : $this->populateResultUrls($item['Urls']),
                'Name' => isset($item['Name']) ? (string) $item['Name'] : null,
                'Id' => isset($item['Id']) ? (string) $item['Id'] : null,
                'Face' => empty($item['Face']) ? null : new ComparedFace([
                    'BoundingBox' => empty($item['Face']['BoundingBox']) ? null : new BoundingBox([
                        'Width' => isset($item['Face']['BoundingBox']['Width']) ? (float) $item['Face']['BoundingBox']['Width'] : null,
                        'Height' => isset($item['Face']['BoundingBox']['Height']) ? (float) $item['Face']['BoundingBox']['Height'] : null,
                        'Left' => isset($item['Face']['BoundingBox']['Left']) ? (float) $item['Face']['BoundingBox']['Left'] : null,
                        'Top' => isset($item['Face']['BoundingBox']['Top']) ? (float) $item['Face']['BoundingBox']['Top'] : null,
                    ]),
                    'Confidence' => isset($item['Face']['Confidence']) ? (float) $item['Face']['Confidence'] : null,
                    'Landmarks' => !isset($item['Face']['Landmarks']) ? null : $this->populateResultLandmarks($item['Face']['Landmarks']),
                    'Pose' => empty($item['Face']['Pose']) ? null : new Pose([
                        'Roll' => isset($item['Face']['Pose']['Roll']) ? (float) $item['Face']['Pose']['Roll'] : null,
                        'Yaw' => isset($item['Face']['Pose']['Yaw']) ? (float) $item['Face']['Pose']['Yaw'] : null,
                        'Pitch' => isset($item['Face']['Pose']['Pitch']) ? (float) $item['Face']['Pose']['Pitch'] : null,
                    ]),
                    'Quality' => empty($item['Face']['Quality']) ? null : new ImageQuality([
                        'Brightness' => isset($item['Face']['Quality']['Brightness']) ? (float) $item['Face']['Quality']['Brightness'] : null,
                        'Sharpness' => isset($item['Face']['Quality']['Sharpness']) ? (float) $item['Face']['Quality']['Sharpness'] : null,
                    ]),
                    'Emotions' => !isset($item['Face']['Emotions']) ? null : $this->populateResultEmotions($item['Face']['Emotions']),
                    'Smile' => empty($item['Face']['Smile']) ? null : new Smile([
                        'Value' => isset($item['Face']['Smile']['Value']) ? filter_var($item['Face']['Smile']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                        'Confidence' => isset($item['Face']['Smile']['Confidence']) ? (float) $item['Face']['Smile']['Confidence'] : null,
                    ]),
                ]),
                'MatchConfidence' => isset($item['MatchConfidence']) ? (float) $item['MatchConfidence'] : null,
                'KnownGender' => empty($item['KnownGender']) ? null : new KnownGender([
                    'Type' => isset($item['KnownGender']['Type']) ? (string) $item['KnownGender']['Type'] : null,
                ]),
            ]);
        }

        return $items;
    }

    /**
     * @return ComparedFace[]
     */
    private function populateResultComparedFaceList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new ComparedFace([
                'BoundingBox' => empty($item['BoundingBox']) ? null : new BoundingBox([
                    'Width' => isset($item['BoundingBox']['Width']) ? (float) $item['BoundingBox']['Width'] : null,
                    'Height' => isset($item['BoundingBox']['Height']) ? (float) $item['BoundingBox']['Height'] : null,
                    'Left' => isset($item['BoundingBox']['Left']) ? (float) $item['BoundingBox']['Left'] : null,
                    'Top' => isset($item['BoundingBox']['Top']) ? (float) $item['BoundingBox']['Top'] : null,
                ]),
                'Confidence' => isset($item['Confidence']) ? (float) $item['Confidence'] : null,
                'Landmarks' => !isset($item['Landmarks']) ? null : $this->populateResultLandmarks($item['Landmarks']),
                'Pose' => empty($item['Pose']) ? null : new Pose([
                    'Roll' => isset($item['Pose']['Roll']) ? (float) $item['Pose']['Roll'] : null,
                    'Yaw' => isset($item['Pose']['Yaw']) ? (float) $item['Pose']['Yaw'] : null,
                    'Pitch' => isset($item['Pose']['Pitch']) ? (float) $item['Pose']['Pitch'] : null,
                ]),
                'Quality' => empty($item['Quality']) ? null : new ImageQuality([
                    'Brightness' => isset($item['Quality']['Brightness']) ? (float) $item['Quality']['Brightness'] : null,
                    'Sharpness' => isset($item['Quality']['Sharpness']) ? (float) $item['Quality']['Sharpness'] : null,
                ]),
                'Emotions' => !isset($item['Emotions']) ? null : $this->populateResultEmotions($item['Emotions']),
                'Smile' => empty($item['Smile']) ? null : new Smile([
                    'Value' => isset($item['Smile']['Value']) ? filter_var($item['Smile']['Value'], \FILTER_VALIDATE_BOOLEAN) : null,
                    'Confidence' => isset($item['Smile']['Confidence']) ? (float) $item['Smile']['Confidence'] : null,
                ]),
            ]);
        }

        return $items;
    }

    /**
     * @return Emotion[]
     */
    private function populateResultEmotions(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Emotion([
                'Type' => isset($item['Type']) ? (string) $item['Type'] : null,
                'Confidence' => isset($item['Confidence']) ? (float) $item['Confidence'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return Landmark[]
     */
    private function populateResultLandmarks(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Landmark([
                'Type' => isset($item['Type']) ? (string) $item['Type'] : null,
                'X' => isset($item['X']) ? (float) $item['X'] : null,
                'Y' => isset($item['Y']) ? (float) $item['Y'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultUrls(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
